<template>
    <div class="text-sm w-full relative" style="height: 100px; font-variant-numeric: tabular-nums;">
        <div
            v-for="(dataMapping, index) in dataMappings"
            :key="dataMapping.label"
            class="bg-gray-200 bottom-0 absolute top-0 w-px"
            :style="dataMappingStyles(index)"
        >
            <div class="absolute whitespace-no-wrap" style="bottom: -27px; left: 50%; transform: translateX(-50%)">{{ dataMapping.label }}</div>

            <div class="bg-blue-500 rounded-sm text-white font-bold absolute text-center z-10 text-4xs" style="left: 50%; padding: 0 3px; bottom: {{ (dataMapping.value / (maxValue ?: 1)) * 100 }}%; transform: translateX(-50%) translateY(6px)">
                {{ dataMapping.value }}
            </div>
        </div>

        <div class="absolute left-0 right-0 h-px bg-gray-200 text-4xs" style="bottom: 50%"></div>
        <div class="absolute left-0 right-0 h-px bg-gray-200 text-4xs" style="bottom: 0"></div>

        <svg class="h-full relative w-full" :viewBox="`0 0 ${dataMappings.length - 1} ${maxValue}`" preserveAspectRatio="none">
            <defs>
                <linearGradient id="background" x1="0%" y1="0%" x2="0%" y2="100%">
                    <stop offset="0%" style="stop-color: #19a1e6; stop-opacity: 0.6;" />
                    <stop offset="100%" style="stop-color: #19a1e6; stop-opacity: 0.2;" />
                </linearGradient>
            </defs>

            <path :d="`M 0 ${maxValue + 1} ${points} L 23 ${maxValue + 1} Z`" fill="url(#background)" />

            <path
                :d="pathD"
                fill="none"
                stroke="#19a1e6"
                strokeOpacity="0.3"
                strokeWidth="1"
                strokeLinecap="round"
                vectorEffect="non-scaling-stroke"
            />
        </svg>
    </div>
</template>

<script>
export default {
    name: 'chart',

    props: {
        dataMappings: Object,
    },

    computed: {
        maxValue() {
            // collect($data)->map(function ($point) { return $point[0]; })->max()

            return 100
        },

        points() {
            // collect($data)->map(function ($point, $index) use ($maxValue) { return "L {$index} ". ($maxValue - $point[0]); })->join(' ')

            return Object.entries(this.dataMappings)
                .map((point, index) => {
                    return `L ${index} ` + (this.maxValue - point[0])
                })
                .join(' ')
        },

        pathD() {
            return this.points.replace('L', 'M')
        },
    },

    mounted() {
        console.log('mounted the chart')
    },

    methods: {
        dataMappingStyles(index) {
            // left: {{ ($index / ((count($data) - 1) ?: 1)) * 100 }}%

            let wip = (index / (this.dataMappings.length - 1)) * 100

            return `left: ${wip}%`
        },
    },
}
</script>
