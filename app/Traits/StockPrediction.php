<?php

namespace App\Traits;

use Rubix\ML\Classifiers\SVC;
use Rubix\ML\CrossValidation\KFold;
use Rubix\ML\CrossValidation\Metrics\RSquared;
use Rubix\ML\CrossValidation\Metrics\SMAPE;
use Rubix\ML\CrossValidation\Reports\ErrorAnalysis;
use Rubix\ML\CrossValidation\Reports\MulticlassBreakdown;
use Rubix\ML\Datasets\Labeled;
use Rubix\ML\Datasets\Unlabeled;
use Rubix\ML\Kernels\SVM\Polynomial;
use Rubix\ML\Regressors\GradientBoost;
use Rubix\ML\Regressors\RegressionTree;
use Rubix\ML\Regressors\SVR;
use Rubix\ML\Transformers\VarianceThresholdFilter;

trait StockPrediction
{
    use StockIndicators;

    public $estimator;
    private $isRegressor = true;

    public function getData()
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
        $tuner = new RegressionTree(7);
        $model = new SVR(0.01, 1, new Polynomial(1, 0, 0.1), true, 1e-3, 256.0);

        return new GradientBoost($tuner, 0.1, 0.8, 1000, 1e-4, 10, 0.1, new RSquared(), $model);
    }

    private function classifier()
    {
        // This classifier is not going to be used for anything just yet, as its mean F1 score of .13 is too low to
        // inspire confidence in me as to the accuracy of its projections. PHP-ML's classifier will be used for the time
        // being, also because of its ability to produce probabilistic estimates for all outcomes, not just the most
        // likely one. Work may be done in the future to increase the accuracy of this model and utilize it as a form of
        // confidence check for its' php-ml counterpart.
        return new SVC(0.01, new Polynomial(1, 0), true, 1e-3, 256.0);
    }

    public function createEstimator()
    {
        $this->estimator = $this->isRegressor ? $this->regressor() : $this->classifier();
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

    public function predict($sample = false)
    {
        $this->createEstimator();
        $this->trainEstimator();
        $sample = $sample ? $sample : $this->getSamples()->last();
        $dataPoint = $this->formatDataPoint($sample);

        return $this->estimator->predict($dataPoint);
    }

    public function testEstimator($metric = false)
    {
        $this->createEstimator();
        $validator = new KFold(5);

        return $validator->test($this->estimator, $this->getDataset(), $metric ? $metric : new SMAPE());
    }

    public function getAllPredictions()
    {
        $this->createEstimator();
        $this->trainEstimator();
        $samples = $this->getSamples();
        $samples->forget($samples->count() - 1);
        $dataset = new Unlabeled($samples->toArray());

        return $this->estimator->predict($dataset);
    }

    public function setEstimator($profitOrPrice)
    {
        $this->isRegressor = $profitOrPrice === 'price';

        return $this;
    }

    public function generateReport()
    {
        $predictions = $this->getAllPredictions();
        $labels = $this->getLabels();
        $labels->forget(0);
        $report = $this->isRegressor ? new ErrorAnalysis() : new MulticlassBreakdown();

        return $report->generate($predictions, $labels->values()->toArray());
    }
}
