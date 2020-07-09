<?php

namespace App\Traits;

use Rubix\ML\Classifiers\MultilayerPerceptron;
use Rubix\ML\Classifiers\SVC;
use Rubix\ML\CrossValidation\KFold;
use Rubix\ML\CrossValidation\Metrics\MCC;
use Rubix\ML\CrossValidation\Metrics\SMAPE;
use Rubix\ML\CrossValidation\Reports\ErrorAnalysis;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Kernels\SVM\Polynomial;
use Rubix\ML\NeuralNet\ActivationFunctions\LeakyReLU;
use Rubix\ML\NeuralNet\ActivationFunctions\ReLU;
use Rubix\ML\NeuralNet\CostFunctions\CrossEntropy;
use Rubix\ML\NeuralNet\CostFunctions\LeastSquares;
use Rubix\ML\NeuralNet\Layers\Activation;
use Rubix\ML\NeuralNet\Layers\Dense;
use Rubix\ML\NeuralNet\Layers\Dropout;
use Rubix\ML\NeuralNet\Layers\PReLU;
use Rubix\ML\NeuralNet\Optimizers\Adam;
use Rubix\ML\Regressors\MLPRegressor;
use Rubix\ML\Regressors\SVR;
use Rubix\ML\Transformers\VarianceThresholdFilter;

trait PriceEstimator
{
    use StockIndicators;

    public $estimator;
    private $isRegressor = true;

    private function getData()
    {
        $nextDay = $this->nextDayHistoricalProfitability();

        return $this->formatProfitabilityAndIndicators($nextDay);
    }

    public function getSamples()
    {
        if ($this->isRegressor) {
            return $this->trendIndicators(true);
        }

        return $this->getData()['indicators'];
    }

    public function getLabels()
    {
        if ($this->isRegressor) {
            return $this->getSamples()->pluck('close');
        }

        return $this->getData()['profitability'];
    }

    public function getDataset()
    {
        $samples = $this->getSamples();
        if ($this->isRegressor) {
            $labels = $samples->pluck('close');
            $labels->forget(0);
            $samples->forget($samples->count() - 1);
        } else {
            $labels = $this->getLabels();
        }

        return new Labeled($samples->toArray(), $labels->toArray());
    }

    private function regressor()
    {
//        return new MLPRegressor([
//            new Dense(20),
//            new Activation(new ReLU()),
//            new Dense(10),
//            new Activation(new ReLU()),
//            new Dense(20),
//            new Activation(new ReLU()),
//            new Dense(40),
//            new Activation(new ReLU()),
//            new Dense(80),
//            new Activation(new ReLU()),
//            new Dense(40),
//            new Activation(new ReLU()),
//            new Dense(20),
//            new Activation(new ReLU()),
//        ], 128, new Adam(0.001), 1e-3, 100, 1e-5, 3, 0.1, new LeastSquares(), new SMAPE());
        return new SVR(3.7, 0.03, new Polynomial(1, 0, 12), true, 1e-3, 256.0);
    }

    private function classifier()
    {
//        return new MultilayerPerceptron([
//            new Dense(200),
//            new Activation(new LeakyReLU()),
//            new Dropout(0.3),
//            new Dense(100),
//            new Activation(new LeakyReLU()),
//            new Dropout(0.3),
//            new Dense(50),
//            new PReLU(),
//        ], 128, new Adam(0.001), 1e-4, 1000, 1e-3, 3, 0.1, new CrossEntropy(), new MCC());
        return new SVC(0.01, new Polynomial(1, 0), true, 1e-3, 256.0);
    }

    public function createEstimator($regressor = true)
    {
        $this->isRegressor = $regressor;
        $this->estimator = $regressor ? $this->regressor() : $this->classifier();
    }

    public function trainEstimator()
    {
        $numberFeatures = count($this->getSamples()->first());
        $this->estimator->train($this->getDataset()->apply(new VarianceThresholdFilter($numberFeatures)));
    }

    public function formatDataPoint($sample)
    {
        return new Unlabeled([array_values($sample)]);
    }

    public function predict($price = true)
    {
        $this->createEstimator($price);
        $this->trainEstimator();
        $sample = $this->getSamples()->last();
        $dataPoint = $this->formatDataPoint($sample);

        return $this->estimator->predict($dataPoint);
    }

    public function testEstimator($metric = false)
    {
        $this->createEstimator();
        $validator = new KFold(5);

        return $validator->test($this->estimator, $this->getDataset(), $metric ? $metric : new SMAPE());
    }

    public function getAllPredictions($regressor = true)
    {
        $this->createEstimator($regressor);
        $this->trainEstimator();
        $samples = $this->getSamples();
        $samples->forget($samples->count() - 1);
        $dataset = new Unlabeled($samples->toArray());

        return $this->estimator->predict($dataset);
    }

    public function generateReport($regressor = true)
    {
        $predictions = $this->getAllPredictions($regressor);
        $labels = $this->getLabels();
        $labels->forget(0);
        $report = $regressor ? new ErrorAnalysis() : new MulticlassBreakdown();

        return $report->generate($predictions, $labels->values()->toArray());
    }
}
