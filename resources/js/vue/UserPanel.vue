<template>
    <div style="width: 100%; text-align: center">
        <template v-if="typeAnswer !== null">


            <template v-if="typeAnswer === gameTypes['solo']">
                <div @click="sendAnswer()"
                     :class="{'user_big_button': true, 'success_send': successSend}">Click</div>
                <div class="text_event_wrapper" v-if="successSend === false">
                    <div class="text_event">Кто не успел, тот опоздал!</div>
                </div>
            </template>
            <template v-else>
                <div :class="{
                    'process': true,
                    'process-active': statusPlay
                }">

                    <template v-if="statusPlay">
                        <span> Идёт приём вариантов</span>
                    </template>
                    <template v-else>
                        Руки прочь от экрана
                    </template>

                </div>
                <div class="answers">
                    <div v-for="item of variables"
                         :class="{
                            'answer': true,
                            'answer-active': item.active,
                        }"
                         @click="sendAnswer(item.sys_value)" :data-answer="item.sys_value">{{ item.value }}</div>
                </div>
            </template>
        </template>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    name: "UserPanel",
    data(){
        return {
            urlCheckMetaData: '',
            urlSendAnswer: '',
            urlLogout: '',
            typeAnswer: null,
            statusPlay: false,
            canAnswer: true,
            sendingRequestSync: false,
            sendingRequestAnswer: false,
            successSend: null,
            variables: [],
            gameTypes: {}
        }
    },
    methods: {
        getVariables(){
            let variables = [];
            let count = 0;
            if(this.typeAnswer === this.gameTypes['choose']){
                count = 4;
            }else if(this.typeAnswer === this.gameTypes['choose_from_two']){
                count = 2;
            }
            if(count === 2){
                variables.push({
                    value: 'Рэпер',
                    sys_value: 1,
                    active: false
                });
                variables.push({
                    value: 'Поэт',
                    sys_value: 2,
                    active: false
                });
            }else{
                for(let i = 0; i < count; i++){
                    variables.push({
                        sys_value: i + 1,
                        value: 'Ответ №' + (i + 1),
                        active: false
                    },);
                }
            }


            return variables;
        },
        checkMetaData(){
            if(!this.sendingRequestSync){
                this.sendingRequestSync = true;

                axios.get(this.urlCheckMetaData)
                        .then((res) => {
                            this.statusPlay = res.data.status;
                            this.typeAnswer = res.data.typeAnswer;
                            this.canAnswer = res.data.canAnswer;

                            if(this.canAnswer && this.successSend !== null){
                                this.successSend = null;
                            }
                        })
                        .catch((err) => {
                            console.log(err);
                        })
                        .finally(() => {
                            this.sendingRequestSync = false;
                        });
            }
        },
        sendAnswer(number = null){
            if(this.statusPlay && !this.sendingRequestAnswer && this.successSend === null){
                if(number !== null){
                    this.activateValue(number);
                }
                let sendValue = number;

                this.sendingRequestAnswer = true;
                axios.post(this.urlSendAnswer, {number_variable: sendValue })
                        .then((res) => {
                            this.successSend = res.data.success;
                            console.log(this.successSend);
                        })
                        .catch((err) => {
                            console.dir(err);
                        })
                        .finally(() => {
                            this.sendingRequestAnswer = false;
                        })
            }
        },
        activateValue(number){
            for(let item of this.variables){
                if(item.sys_value !== number){
                    item.active = false;
                }else{
                    item.active = true;
                }
            }
        }
    },
    mounted() {
        this.urlCheckMetaData = window.pageData.urlCheckMetaData;
        this.urlSendAnswer = window.pageData.urlSendAnswer;
        this.urlLogout = window.pageData.urlLogout;
        this.gameTypes = window.pageData.gameTypes;

        setInterval(() => {
            this.checkMetaData();
        },500);
    },
    watch: {
        typeAnswer(){
            this.variables = this.getVariables();
            this.sendingRequestAnswer = false;
            this.activeModal = false;
            this.statusPlay = false;
            this.activateValue(0);
        }
    }
}
</script>

<style scoped>

</style>
