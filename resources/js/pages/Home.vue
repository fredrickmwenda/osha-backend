<template>
    <div class="container">
        <section class="content-header">
            <h1>
                Dashboard
            </h1>
            <ol class="breadcrumb">
                <li>
                    <router-link :to="'/'"
                        ><i class="fa fa-dashboard"></i> Home</router-link
                    >
                </li>
                <li><a href="#">Dashboard</a></li>
            </ol>
        </section>

        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="row">
                                <!-- FORM FILTER By  MONTHS AND YEARS-->
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Month</label>
                                        <select
                                            v-model="month"
                                            class="form-control"
                                        >
                                            <option value="01">Januari</option>
                                            <option value="02">February</option>
                                            <option value="03">March</option>
                                            <option value="04">April</option>
                                            <option value="05">May</option>
                                            <option value="06">June</option>
                                            <option value="07">July</option>
                                            <option value="08">August</option>
                                            <option value="09"
                                                >September</option
                                            >
                                            <option value="10">October</option>
                                            <option value="11">November</option>
                                            <option value="12">December</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="">Years</label>
                                        <select
                                            v-model="year"
                                            class="form-control"
                                        >
                                            <option
                                                v-for="(y, i) in years"
                                                :key="i"
                                                :value="y"
                                                >{{ y }}</option
                                            >
                                        </select>
                                    </div>
                                </div>
                                <!-- FILTER FORM BY MONTH AND YEAR -->

                                <!-- BUTTON TO EXPORT DATA TO EXCEL -->
                                <div class="col-md-2">
                                    <button
                                        class="btn btn-primary btn-sm pull-right"
                                        @click="exportData"
                                    >
                                        Export
                                    </button>
                                </div>
                                <!-- BUTTON TO EXPORT DATA TO EXCEL -->
                            </div>
                        </div>
                        <div class="panel-body">
                            <!-- SHOW CHART OF PREVIOUSLY CREATED COMPONENTS -->
                            <!-- BY SENDING DATA, OPTIONS AND LABELS AS PROPS -->
                            <line-chart
                                v-if="transactions.length > 0"
                                :data="transaction_data"
                                :options="chartOptions"
                                :labels="labels"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</template>
<script>
import moment from "moment";
import _ from "lodash";
import LineChart from "../components/LineChart.vue";
import { mapActions, mapState } from "vuex";
export default {
    created() {
        this.getChartData({
            month: this.month,
            year: this.year
        });
    },
    data() {
        return {
            chartOptions: {
                responsive: true,
                maintainAspectRatio: false
            },
            month: moment().format("MM"),
            year: moment().format("Y")
        };
    },
    watch: {
        month() {
            this.getChartData({
                month: this.month,
                year: this.year
            });
        },
        year() {
            this.getChartData({
                month: this.month,
                year: this.year
            });
        }
    },
    computed: {
        ...mapState("dashboard", {
            transactions: state => state.transactions
        }),
        ...mapState(['token']),
        years() {
            return _.range(
                2010,
                moment()
                    .add(1, "years")
                    .format("Y")
            );
        },
        labels() {
            return _.map(this.transactions, function(o) {
                return moment(o.date).format("DD");
            });
        },
        transaction_data() {
            return _.map(this.transactions, function(o) {
                return o.total;
            });
        }
    },
    methods: {
        ...mapActions("dashboard", ["getChartData"]),
        exportData() {
            window.open(
                `/api/export?api_token=${this.token}&month=${this.month}&year=${this.year}`
            );
        }
    },
    components: { "line-chart": LineChart }
};
</script>
