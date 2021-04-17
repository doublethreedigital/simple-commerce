<template>
    <div>
        <header class="mb-3 flex items-center justify-between">
            <h1>Sales Report</h1>

            <div class="flex items-center">
                <div class="select-input-container w-32">
                    <select v-model="currentView" class="select-input">
                        <option value="day">Today</option>
                        <option value="week">This week</option>
                        <option value="month">Last month</option>
                    </select>

                    <div class="select-input-toggle">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                            <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z"></path>
                        </svg>
                    </div>
                </div>

                <button
                    class="btn-primary ml-2"
                    @click.native="csvExport"
                >Export</button>
            </div>
        </header>

        <div class="card p-2 content mb-4">
            <div class="flex flex-wrap -mx-2 mb-4">
                <div class="px-4 py-2 w-full">
                    <div v-show="currentView === 'day'">
                        <slot name="day"></slot>
                    </div>

                    <div v-show="currentView === 'week'">
                        <slot name="week"></slot>
                    </div>

                    <div v-show="currentView === 'month'">
                        <slot name="month"></slot>
                    </div>
                </div>
            </div>
        </div>

        <div class="card p-0 mb-4">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Summary</th>
                        <th>Sales</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="salesTableRow in salesTableRows" :key="salesTableRow.label">
                        <td><a href="#">{{ salesTableRow.display }}</a></td>
                        <td>{{ salesTableRow.sales }} sales</td>
                        <td>{{ salesTableRow.total }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
export default {
    name: 'sales-report',

    props: {
        salesPastMonth: Object,
        salesPastWeek: Object,
        salesPastDay: Object,
    },

    data() {
        return {
            currentView: 'day',
        }
    },

    computed: {
        salesTableRows() {
            return [
                {
                    label: 'Jan 2021',
                    display: '1st to 31st January 2021',
                    start: '2021-01-01',
                    end: '2020-01-31',
                    sales: 52,
                    total: '£154.99',
                }
            ]
        },
    },

    methods: {
        csvExport() {
            // TODO: csv export
        },
    },
}
</script>
