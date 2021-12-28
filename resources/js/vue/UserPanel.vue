<template>
    <div id="root" class="container">

        <div>
            <div v-if="typeAnswer !== null">
                <a :href="urlLogout" class="exit-btn">ВЫйТИ</a>

                <template v-if="typeAnswer === gameTypes['solo']">
                    <div @click="sendAnswer()"
                         :class="{'user_big_button': true, 'success_send': successSend}">Click</div>
                    <div class="text_event_wrapper" v-if="successSend === false">
                        <div class="text_event">Кто не успел, тот опоздал!</div>
                    </div>
                </template>
                <template v-else>
                    <div class="process">Приём вариантов...</div>
                        <div v-for="item of variables"
                             :class="{
                                'answer-default': !item.active,
                                'answer-active': item.active,
                            }"
                             @click="sendAnswer(item.value)">{{ item.value }}</div>
                </template>
            </div>
        </div>
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
            for(let i = 0; i < count; i++){
                variables.push({
                    value: i + 1,
                    active: false
                },);
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
                if(item.value !== number){
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
        },1500);
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
