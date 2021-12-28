<template>
    <div class="score_block">
        <div class="score_count_wrapper">
            <div class="score_title">Change Score</div>
            <div class="count_block">
                <input type="text" placeholder="Number of Points" @input="setScore($event)" :value="form.points">
            </div>
            <div class="count_block">
                <select name="username" id="username" v-model="form.gamer">
                    <option v-for="gamer of gamersList"
                            :value="gamer.id">{{ gamer.name}}</option>
                </select>
            </div>
            <div class="count_buttons">
                <div class="count_button" @click="more()">Add Points</div>
                <div class="count_button" @click="less()">Subtract Points</div>
            </div>
            <div class="results_block">
                <ul>
                    <li v-for="gamer of gamersList">
                        <div>{{ gamer.name }}:</div>
                        <div>{{ gamer.score }}</div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";

export default {
    name: "ScoreManipulate",
    data(){
        return {
            gamersList: [],
            urlCheckUsersData: '',
            urlUpdateScore: '',
            sendingRequestSync: false,
            sendingRequestAction: false,
            form: {
                points: 100,
                gamer: 0
            }
        }
    },
    methods: {
        setScore($event){
            let value = parseInt($event.target.value);
            if(value){
                this.form.points = value;
            }
        },
        more(){
            if(!this.sendingRequestAction) {
                this.sendingRequestAction = true;
                axios.post(this.urlUpdateScore, {
                    'value': Math.abs(this.form.points),
                    'user_id': this.form.gamer,
                    'method': 'add'
                })
                    .then((res) => {
                        this.form.points = 0;
                        this.form.gamer = 0;
                        console.log('Успешно обновил счет')
                    })
                    .catch((err) => {
                        console.log(err);
                    })
                    .finally(() => {
                        this.sendingRequestAction = false;
                    });
            }
        },
        less(){
            if(!this.sendingRequestAction) {
                this.sendingRequestAction = true;
                axios.post(this.urlUpdateScore, {
                    'value': Math.abs(this.form.points),
                    'user_id': this.form.gamer,
                    'method': 'less'
                })
                    .then((res) => {
                        this.form.points = 0;
                        this.form.gamer = 0;
                        console.log('Успешно обновил счет')
                    })
                    .catch((err) => {
                        console.log(err);
                    })
                    .finally(() => {
                        this.sendingRequestAction = false;
                    });
            }
        },
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
                                    gamer.score = newDataGamer.score;

                                }
                            }
                        }
                        this.gamersList.sort((a,b) => {
                            if (a.score < b.score) {
                                return 1;
                            }
                            if (a.score > b.score) {
                                return -1;
                            }
                            return 0;
                        });
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
    },
    mounted() {
        this.urlCheckUsersData = window.pageData.urlCheckUsersData;
        this.urlUpdateScore = window.pageData.urlUpdateScore;
        let listGamers = window.pageData.listGamers;
        for(let gamer of listGamers){
            this.gamersList.push({
                id: gamer.id,
                name: gamer.name,
                is_answering: false,
                answering: null,
                score: gamer.score ? gamer.score : 0,

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
