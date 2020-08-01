<template>
    <el-container class="portfolios">
        <el-header>
            <h2>Portfolios</h2>
        </el-header>
        <el-main>
            <dashboard-portfolio-card
                v-for="(portfolio, index) in portfolios"
                :key="'pc-'+index+'-'+portfolio.updated_at"
                :title="portfolio.platform"
                :value="portfolio.portfolio_value"
                :updated="portfolio.updated_at"
                :loading="false"
            >
            </dashboard-portfolio-card>
        </el-main>
    </el-container>
</template>

<script>
    export default {
        name: "DashboardPortfolios",
        props: ["initial_portfolios"],
        data () {
            return {
                portfolios: []
            };
        },
        methods: {
            updatePortfolios (portfolios) {
                this.portfolios = portfolios;
            }
        },
        mounted () {
            let self = this,
                portfolios = JSON.parse(this.initial_portfolios);

            this.updatePortfolios(portfolios);
            this.$echo.channel('portfolios').listen('PortfoliosUpdated', (payload) => {
                self.updatePortfolios(payload.portfolios);
            });
        }
    }
</script>

<style scoped>

</style>
