<template>
    <el-card class="portfolio" shadow="hover">
        <div slot="header">
            <h3 class="capitalize">
                {{ title }}
                <i :class="iconClass + ' circle padding transition material-shadow right'" @click="refresh"></i>
            </h3>
        </div>
        <p>Value: <strong>${{ value }}</strong></p>
        <span>Last Updated {{ updated }}</span>
    </el-card>
</template>

<script>
    import ElementUI from 'element-ui'
    export default {
        name: "DashboardPortfolioCard",
        props: ['title', 'value', 'updated'],
        components: {
            ElementUI,
        },
        data () {
            return {
                loading: false,
            }
        },
        computed: {
            iconClass () {
                return this.loading ?
                    'el-icon-loading' :
                    'el-icon-refresh cursor material-hover';
            }
        },
        methods: {
            refresh () {
                if (!this.loading) {
                    this.$http.get('api/process/refresh').then(() => {
                        this.loading = true;
                    }).catch((error) => {
                        this.$notify.error({
                            title: 'Error',
                            message: error.body.message
                        });
                    });
                }
            }
        }
    }
</script>

<style scoped lang="scss">
    .portfolio {

        .el-card__header {

            padding-top: 5px;
            padding-bottom: 5px;

        }

        i {

            border: none;
            margin-top: -10px;

        }

        p {

            margin-top: 0;
            margin-bottom: 0.5em;
        }

        span {

            font-size: 0.7em;

        }

    }
</style>