w<template>
    <el-card shadow="always">
        <div slot="header">
            <h3>Portfolios</h3>
        </div>
        <dashboard-portfolio-card
                v-for="(portfolio, index) in portfolios"
                :key="'pc-'+index+'-'+portfolio.updated_at"
                :title="portfolio.platform"
                :value="portfolio.portfolio_value"
                :updated="portfolio.updated_at"
                :loading="false"
        >
        </dashboard-portfolio-card>
    </el-card>
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
