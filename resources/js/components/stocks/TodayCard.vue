<template>
    <div :class="profitOrLoss + ' stock-card bright-background material-shadow'">
        <div class="stock-card__header">
            <h1>Today</h1>
            <span>As of {{ stock.lastUpdatedAt }}</span>
            <a href="#">Refresh</a>
        </div>
        <div class="stock-card__body">
            <div class="stock-card__body__price">
                <h1>${{ stock.quickLook.price }}</h1>
                <span>
                    {{ stock.lastUpdate.change > 0 ? '+' : '' }}{{ stock.lastUpdate.change }}
                    ({{ stock.lastUpdate.change_percent }}%)
                </span>
            </div>
            <div class="stock-card__body__verdict">
                <h1>{{ verdict }}</h1>
            </div>
            <div class="stock-card__body__data">
                <div class="overflow-hidden">
                    <div class="half-width text-center left">
                        Open: ${{ stock.lastUpdate.open }} <br />
                        High: ${{ stock.lastUpdate.high }}
                    </div>
                    <div class="half-width text-center right">
                        Low: ${{ stock.lastUpdate.low }} <br />
                        Close: ${{ stock.lastUpdate.close }}
                    </div>
                </div>
                <div class="full-width text-center">
                    Volume: {{ stock.lastUpdate.volume }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['stock'],
        computed: {
            profitOrLoss: function () {
                return this.stock.lastUpdate.change >= 0 ? 'profit' : 'loss';
            },
            verdict: function () {
                let percentageChange = Math.abs(this.stock.lastUpdate.change_percent),
                    magnitude = 'small';
                if (percentageChange >= 5) {
                    magnitude = 'large';
                } else if (percentageChange >= 1) {
                    magnitude = 'moderate';
                } else if (percentageChange === 0) {
                    return 'no change';
                }
                return magnitude + ' ' + this.profitOrLoss;
            },
        },
    }
</script>

<style scoped lang="scss">
    @import '../../../sass/variables';

    .stock-card {
        padding: 10px 15px;
        border-radius: 5px;
        color: $dark;

        h1 {
            display: inline-block;
            font-weight: normal;
            font-size: 40px;
            margin: 0;
        }

        &__header {
            padding-bottom: 10px;

            h1 {
                padding-left: 10px;
            }

            span {
                padding-left: 10px;
            }

            a {
                display: inline-block;
                float: right;
                margin-top: 23px;
                color: $dark;
                padding-right: 10px;
            }
        }

        &__body {

            &__price {
                padding: 10px;
                border-radius: 5px;
                margin-top: 11px;
                border: 1px solid $body-bg;

                h1 {
                    display: inline-block;
                }

                span {
                    line-height: 50px;
                    float: right;
                }
            }

            &__verdict {
                h1 {
                    display: block;
                    text-align: center;
                    margin-top: 20px;
                    margin-bottom: 20px;
                    text-transform: capitalize;
                }
            }

            &__data {
                max-width: 300px;
                margin: 0 auto;
            }

        }

        &.profit {
            border-bottom: 15px solid $profit-green;
        }

        &.loss {
            border-bottom: 15px solid $loss-red;
        }
    }
</style>
