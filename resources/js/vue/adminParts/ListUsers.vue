<template>
    <div class="users_block">
        <div class="users_wrapper">
            <template v-for="gamer of gamersList">
                <div :class="{
                        'user': true,
                        'user_offline': !gamer.online,
                        'red_bg': gamer.email === 'red@red',
                        'yellow_bg': gamer.email === 'black@black',
                        'green_bg': gamer.email === 'green@green',
                        'blue_bg': gamer.email === 'blue@blue',
                    }" >
                    <template v-if="gamer.is_answering && gamer.answering === null">
                        <div class="user__answer">Нажал первым</div>
                    </template>
                    <template v-if="gamer.is_answering && gamer.answering !== null">
                        <div class="user__answer">Выбрал вариант {{ gamer.answering }}</div>
                    </template>
                    <div class="user__name">{{ gamer.name }}</div>
                </div>
            </template>
        </div>
        <div class="game_buttons">
            <div class="game_button" @click="changePlayPause()">{{ statusPlay ? 'Pause' : 'Play' }}</div>
            <div class="game_button" @click="reset()">Reset</div>
            <div class="game_button" @click="changeTypeAnswer()">{{ typeAnswer }}</div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "ListUsers",
    data(){
        return {
            gamersList: [],
            urlCheckUsersData: '',
            urlReset: '',
            urlPlayPause: '',
            urlChangeTypeAnswer: '',
            sendingRequestSync: false,
            sendingRequestAction: false,
            typeAnswer: null,
            statusPlay: false,
        }
    },
    methods: {
        checkUsersData(){
            if(!this.sendingRequestSync){
                this.sendingRequestSync = true;

                axios.get(this.urlCheckUsersData)
                    .then((res) => {
                        let data = res.data.gamers;
                        for(let newDataGamer of data){
                            for(let gamer of this.gamersList){
                                if(gamer.id === newDataGamer.id){
                                    gamer.answering = newDataGamer.answering;
                                    gamer.is_answering = newDataGamer.is_answering;
                                    gamer.online = newDataGamer.online;
                                }
                            }
                        }
                        this.typeAnswer = res.data.meta.typeAnswer;
                        this.statusPlay = res.data.meta.statusPlay;
                    })
                    .catch((err) => {
                        console.log(err);
                    })
                    .finally(() => {
                        this.sendingRequestSync = false;
                    });
            }
        },
        changePlayPause(){
            if(!this.sendingRequestAction){
                this.sendingRequestAction = true;
                axios.post(this.urlPlayPause)
                    .then(() => {
                        this.statusPlay = !this.statusPlay;
                        console.log('Статус изменен');
                    })
                    .catch((err) => {
                        console.dir(err);
                    })
                    .finally(() => {
                        this.sendingRequestAction = false;
                    })

            }
        },
        changeTypeAnswer(){
            if(!this.sendingRequestAction){
                this.sendingRequestAction = true;
                axios.post(this.urlChangeTypeAnswer)
                    .then((res) => {
                        this.typeAnswer = res.data.typeAnswer;
                        console.log('Тип игры успешно изменен');
                    })
                    .catch((err) => {
                        console.dir(err);
                    })
                    .finally(() => {
                        this.sendingRequestAction = false;
                    })

            }
        },
        reset(){
            if(!this.sendingRequestAction){
                this.sendingRequestAction = true;
                axios.post(this.urlReset)
                        .then(() => {
                            console.log('Ответы успешно сброшены');
                        })
                        .catch((err) => {
                            console.dir(err);
                        })
                        .finally(() => {
                            this.sendingRequestAction = false;
                        })

            }
        }
    },
    mounted() {
        this.urlCheckUsersData = window.pageData.urlCheckUsersData;
        this.urlReset = window.pageData.urlReset;
        this.urlPlayPause = window.pageData.urlPlayPause;
        this.urlChangeTypeAnswer = window.pageData.urlChangeTypeAnswer;
        let listGamers = window.pageData.listGamers;
        for(let gamer of listGamers){
            this.gamersList.push({
                id: gamer.id,
                name: gamer.name,
                email: gamer.email,
                is_answering: false,
                answering: null,
                online: gamer.online
            });
        }

        setInterval(() => {
            this.checkUsersData();
        },1000);
    }
}
</script>

<style scoped>

</style>
