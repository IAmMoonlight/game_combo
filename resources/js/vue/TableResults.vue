<template>
    <div class="container">
        <div>
            <div class="table-name">
                <span>Таблица результатов</span>
            </div>
            <div class="table-body">
                <div class="row table-header">
                    <span>N</span>
                    <span>КоМанда</span>
                    <span>ответ</span>
                    <span>очки</span>
                </div>
                <template v-for="(gamer, index) of gamersList">
                    <div :class="{
                        'row': true,
                        'team': true,
                        'pink': gamer.email === 'red@red',
                        'orange': gamer.email === 'black@black',
                        'green': gamer.email === 'green@green',
                        'blue': gamer.email === 'blue@blue',
                    }">
                        <span>{{ index + 1 }}</span>
                        <span>{{ gamer.name }}</span>
                        <span>
                            <template v-if="typeAnswer === 'solo' && gamer.is_answering">
                                {{ 'Нажал первым' }}
                            </template>
                            <template v-else-if="typeAnswer !== 'solo' && gamer.is_answering && !isPlaying" >
                                {{ gamer.answering }}
                            </template>
                        </span>
                        <span>{{ gamer.score }}</span>
                    </div>
                </template>
            </div>
        </div>

    </div>

<!--    <div class="progress_section">-->
<!--        <div class="progress_title">Статус разочарования</div>-->
<!--        <ul class="progress_content">-->
<!--            <li class="progress_block">-->
<!--                <div class="progress_block__place">#</div>-->
<!--                <div class="progress_block__username">Команда</div>-->
<!--                <div class="progress_block__answer">Ответ</div>-->
<!--                <div class="progress_block__points">Очки</div>-->
<!--            </li>-->

<!--            <template v-for="(gamer, index) of gamersList">-->
<!--                <li :class="{-->
<!--                    'progress_block': true,-->
<!--                    'red_bg': gamer.email === 'red@red',-->
<!--                    'yellow_bg': gamer.email === 'black@black',-->
<!--                    'green_bg': gamer.email === 'green@green',-->
<!--                    'blue_bg': gamer.email === 'blue@blue',-->
<!--                }">-->
<!--                    <div class="progress_block__place">-->
<!--                        {{ index + 1 !== 1 ? index + 1 : '♕' }}-->
<!--                    </div>-->
<!--                    <div class="progress_block__username">{{ gamer.name }}</div>-->
<!--                    <div class="progress_block__answer">-->
<!--                        <template v-if="typeAnswer === 'solo' && gamer.is_answering">-->
<!--                            {{ 'Нажал первым' }}-->
<!--                        </template>-->
<!--                        <template v-else-if="typeAnswer !== 'solo' && gamer.is_answering && !isPlaying" >-->
<!--                            Выбрал вариант {{ gamer.answering }}-->
<!--                        </template>-->

<!--                    </div>-->
<!--                    <div class="progress_block__points">{{ gamer.score }}</div>-->
<!--                </li>-->
<!--            </template>-->
<!--        </ul>-->
<!--        <div class="result_text">Победители должны похлопать всем, ибо им случайно поддались!</div>-->
<!--        <div class="joke_text">Если вас не устраивают результаты, то обратитесь к адвокатам.</div>-->
<!--    </div>-->
</template>

<script>
import axios from "axios";

export default {
    name: "TableResults",
    data(){
        return {
            gamersList: [],
            isPlaying: false,
            urlCheckUsersData: '',
            sendingRequestSync: false,
            sendingRequestAction: false,
            typeAnswer: null,
        }
    },
    methods:{
        checkUsersData(){
            if(!this.sendingRequestSync){
                this.sendingRequestSync = true;

                axios.get(this.urlCheckUsersData)
                    .then((res) => {
                        let data = res.data.gamers;
                        this.isPlaying = res.data.meta.statusPlay;
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
        let listGamers = window.pageData.listGamers;
        for(let gamer of listGamers){
            this.gamersList.push({
                id: gamer.id,
                name: gamer.name,
                email: gamer.email,
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
