<template>
    <div class="w-2/3 flex bg-white shadow-lg mb-10">
        <div class="flex-1 p-4">
            <div class="flex items-center justify-center py-10">
                <h3 class="text-grey-darker text-lg mr-6">{{ game.team_home.name }}</h3>
                <p class="font-thin text-3xl">{{ game.score_home }}</p>
            </div>
            <div class="text-grey-darker py-4 flex justify-center items-center">
                <span class="text-semibold text-black text-sm">Attack Counts : </span><span>{{ game.attack_count['home'] }}</span>
            </div>
            <div>
                <table class="w-full text-left table-collapse">
                    <thead>
                        <tr>
                            <th class="text-sm font-medium text-grey-darker p-2 bg-grey-lightest">Players</th>
                            <th class="text-sm font-medium text-grey-darker p-2 bg-grey-lightest">Assist</th>
                            <th class="text-sm font-medium text-grey-darker p-2 bg-grey-lightest">2pts</th>
                            <th class="text-sm font-medium text-grey-darker p-2 bg-grey-lightest">3pts</th>
                            <th class="text-sm font-medium text-grey-darker p-2 bg-grey-lightest">Pts</th>
                        </tr>
                    </thead>
                    <tbody class="align-baseline">
                        <tr v-for="player in game.players_home">
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-grey-darker whitespace-no-wrap">{{ player.name }}</td>
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-grey-darker whitespace-no-wrap">{{ player.assist }}</td>
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-grey-darker whitespace-no-wrap">{{ player.pts['2pts'] }} <span>%</span></td>
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-grey-darker whitespace-no-wrap">{{ player.pts['3pts'] }} <span>%</span></td>
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-grey-darker whitespace-no-wrap">{{ player.score }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="flex-1 text-lg font-semibold px-4 flex flex-col justify-center items-center">
            <div v-show="game.finished">
                <h4 class="text-red font-semibold">Finished</h4>
            </div>
            <span>--</span>
        </div>
        <div class="flex-1 p-4">
            <div class="flex items-center justify-center py-10">
                <p class="font-thin text-3xl mr-6">{{ game.score_away }}</p>
                <h3 class="text-grey-darker text-lg">{{ game.team_away.name }}</h3>
            </div>
            <div class="text-grey-darker py-4 flex justify-center items-center">
                <span class="text-semibold text-black text-sm">Attack Counts : </span><span>{{ game.attack_count['away'] }}</span>
            </div>
            <div>
                <table class="w-full text-left table-collapse">
                    <thead>
                        <tr>
                            <th class="text-sm font-medium text-grey-darker p-2 bg-grey-lightest">Players</th>
                            <th class="text-sm font-medium text-grey-darker p-2 bg-grey-lightest">Assist</th>
                            <th class="text-sm font-medium text-grey-darker p-2 bg-grey-lightest">2pts</th>
                            <th class="text-sm font-medium text-grey-darker p-2 bg-grey-lightest">3pts</th>
                            <th class="text-sm font-medium text-grey-darker p-2 bg-grey-lightest">Pts</th>
                        </tr>
                    </thead>
                    <tbody class="align-baseline">
                        <tr v-for="player in game.players_away">
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-grey-darker whitespace-no-wrap">{{ player.name }}</td>
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-grey-darker whitespace-no-wrap">{{ player.assist }}</td>
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-grey-darker whitespace-no-wrap">{{ player.pts['2pts'] }} <span>%</span></td>
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-grey-darker whitespace-no-wrap">{{ player.pts['3pts'] }} <span>%</span></td>
                            <td class="p-2 border-t border-grey-light font-mono text-xs text-grey-darker whitespace-no-wrap">{{ player.score }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props : ['data'],
        data() {
            return {
                game: this.data,
            }
        },
        async mounted() {
            while(! this.game.finished)
            {
                const promises = [this.attack(), this.getData()]

                await Promise.all(promises)

                // if(this.attack) {
                //     const {data} = await axios.post(`/attack/${this.game.id}`)
                // }

                // this.runAttack()
            }
        },
        methods : {
            async sleep(time) {
                return new Promise(resolve => {
                    setTimeout(() => {
                        resolve('resolved')
                    }, time)
                })
            },

            async getData() {
                await this.sleep(5000)

                const {data} = await axios.get(`/game/${this.game.id}`)
                this.game = data
            },

            async attack() {
                await this.sleep(this.getRandomNumber())

                await axios.post(`/attack/${this.game.id}`)
            },

            getRandomNumber() {
                return Math.floor(Math.random() * (20000 - 10000) + 10);
            }
        }
    }
</script>
