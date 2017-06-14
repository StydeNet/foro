<template>
    <div>
        <form>
            <button @click.prevent="upvote"
                    :class="currentVote == 1 ? 'btn-primary' : 'btn-default'"
                    class="btn">+1</button>
            Puntuación actual: <strong id="current-score">{{ currentScore }}</strong>
            <button @click.prevent="downvote"
                    class="btn btn-default">-1</button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['score', 'vote'],
        data() {
            return {
                currentVote: this.vote,
                currentScore: this.score
            }
        },
        methods: {
            upvote() {
                if (this.currentVote == 1) {
                    this.currentScore--;

                    axios.delete(window.location.href + '/vote');

                    this.currentVote = null;
                } else {
                    this.currentScore++;

                    axios.post(window.location.href + '/upvote')

                    this.currentVote = 1;
                }
            },
            downvote() {

            }
        }
    }
</script>
