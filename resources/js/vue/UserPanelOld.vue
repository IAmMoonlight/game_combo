<template>
    <div>
        <div v-if="typeAnswer !== null">
            <a :href="urlLogout" class="sign_out">Sign Out</a>

            <template v-if="typeAnswer === 'solo'">
                <div @click="sendAnswer()"
                     :class="{'user_big_button': true, 'success_send': successSend}">Click</div>
                <div class="text_event_wrapper" v-if="successSend === false">
                    <div class="text_event">Кто не успел, тот опоздал!</div>
                </div>
            </template>
            <template v-else>
                <div class="user_four_buttons ">
                    <div v-for="item of variables"
                         :class="{'user_little_button': true, 'choice_answer': item.active}"
                         @click="sendAnswer(item.value)">{{ item.value }}</div>
                </div>
            </template>
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
            variables: [
                {
                    value: 1,
                    active: false
                },
                {
                    value: 2,
                    active: false
                },
                {
                    value: 3,
                    active: false
                },
                {
                    value: 4,
                    active: false
                },
            ]
        }
    },
    methods: {
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

        setInterval(() => {
            this.checkMetaData();
        },500);
    },
    watch: {
        typeAnswer(){
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
