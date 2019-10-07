<template>
    <div class="wizard-harry">
        <div class="wizard-wrapper">
            <div class="inner-wizard">
                <div class="wizard-node"
                     v-for="(step, idx) in data"
                     :class="getClassStep">
                    <div class="inner-wizard-node">
                        <div class="wizard-majority" :class="getStepFromIdx(idx) === currentstep ? 'wizard-active' : ''">
                            <div class="wizard-title" @click="(getStepFromIdx(idx) !== currentstep) ? '' : ''">
                                <h1><i :class="step.icon"></i></h1>
                                <h4>{{ step.title }}</h4>
                            </div>

                            <div class="wizard-info">
                                <p>{{ step.value }}</p>
                                <p class="wizard-change" v-show="getStepFromIdx(idx) < currentstep "><a href="#">Change {{step.title}}</a></p>
                            </div>
                        </div>

                        <div class="stupid-arrow-grid" v-show="getStepFromIdx(idx) === currentstep" :class="getStepFromIdx(idx) === currentstep ? 'wizard-active-arrow' : ''">
                            <div class="gahdammit">
                                <div class="inner-peace">
                                    <div class="top-white-space"></div>
                                    <div class="arrow-space">
                                        <div class="inner-arrow-space">
                                            <i class="fas fa-arrow-right white"></i>
                                        </div>
                                    </div>
                                    <div class="bottom-white-space"></div>
                                </div>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "WizardComponent",
        props: ['steps', 'currentstep', 'data'],
        methods: {
            getClassStep(idx) {
                return 'step'+this.getStepFromIdx(idx);
            },
            getStepFromIdx(idx) {
                return parseInt(idx+1)
            }
        },
    }
</script>

<style scoped>
    @media screen {
        .wizard-harry {
            width: 100%;
        }

        .wizard-wrapper {
            margin: 0.5em;
            border: 0.5px solid transparent;
            box-shadow: 1px 1px 10px 1px #ccc;
            border-radius: 0.5em;
        }

        .inner-wizard {
            display: flex;
            flex-flow: row;
            justify-content: center;
            align-items: stretch;
        }

        .wizard-node {
            width: 100%
        }

        .inner-wizard-node {
            margin: 1em;
        }

        .wizard-majority {
            display: flex;
            flex-flow: column;
        }

        .wizard-active {
            background-color: #605ca8;
            color: #fff;
            border-top-left-radius: 0.5em;
            border-bottom-left-radius: 0.5em;
        }

        .wizard-active-arrow {
            background-color: #605ca8;
            border-top-right-radius: 0.5em;
            border-bottom-right-radius: 0.5em;
        }

        .wizard-title > h1 {
            font-size: 1.75em;
        }

        .wizard-title > h4 {
            text-transform: uppercase;
            font-weight: 900;
        }

        .inner-wizard-node {
            display: flex;
            flex-flow: row;
            justify-content: center;
        }

        .wizard-active-arrow .inner-arrow-space {
            border-radius: 1em;
            border: 2px solid black;
            background-color: #fff;
        }
    }

    @media screen and (max-width: 767px){
        .wizard-title > h4 {
            display: none;
        }

        .wizard-majority {
            width: 75%;
        }

        .stupid-arrow-grid {
            padding: 50% 0;
            width: 25%;
        }

    }

    @media screen and (min-width: 768px) and (max-width: 1199px){
        .wizard-majority {
            width: 80%;
        }

        .stupid-arrow-grid {
            padding: 40% 0;
        }

        .stupid-arrow-grid {
            width: 20%;
        }
    }

    @media screen and (min-width: 1200px){
        .wizard-majority {
            width: 90%;
        }

        .stupid-arrow-grid {
            padding: 20% 0;
            width: 10%;
        }

    }
</style>
