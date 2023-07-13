<script setup>

</script>

<template>
    <div class="mh-placeholder-svg-container">
        <svg viewBox="0 0 300 100" preserveAspectRatio="xMidYMid meet">
            <rect clip-path="url(#clipPath-454)" x="0" y="0" width="300" height="100"
                  style="fill: url('#gradient-454');"></rect>
            <defs>
                <clipPath id="clipPath-454">
                    <rect x="0" y="5" rx="4" ry="4" width="300" height="5"></rect>
                    <rect x="0" y="15" rx="4" ry="4" width="300" height="5"></rect>
                    <rect x="0" y="30" rx="4" ry="4" width="300" height="5"></rect>
                    <rect x="0" y="45" rx="4" ry="4" width="300" height="5"></rect>
                    <rect x="0" y="60" rx="4" ry="4" width="300" height="5"></rect>
                    <rect x="0" y="75" rx="4" ry="4" width="300" height="5"></rect>
                    <rect x="0" y="90" rx="4" ry="4" width="300" height="5"></rect>
                    <rect x="0" y="105" rx="4" ry="4" width="300" height="5"></rect>
                    <rect x="0" y="120" rx="4" ry="4" width="300" height="5"></rect>
                </clipPath>
                <linearGradient id="gradient-454">
                    <stop offset="0%" stop-color="#f0f0f0">
                        <animate attributeName="offset" values="-2; 1" dur="2s" repeatCount="indefinite"></animate>
                    </stop>
                    <stop offset="50%" stop-color="#e0e0e0">
                        <animate attributeName="offset" values="-1.5; 1.5" dur="2s" repeatCount="indefinite"></animate>
                    </stop>
                    <stop offset="100%" stop-color="#f0f0f0">
                        <animate attributeName="offset" values="-1; 2" dur="2s" repeatCount="indefinite"></animate>
                    </stop>
                </linearGradient>
            </defs>
        </svg>
    </div>
</template>
