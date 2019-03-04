<template>
    <div>
        <!-- <input type="text" class="input is-large top" v-model="email_client" style="margin-top: 20%;" placeholder="Email client"> -->
        <button class="button is-large is-fullwidth is-danger"  @click="incasare" tip="cash" style="margin-top: 10%;">CASH</button>
        <button class="button is-large is-fullwidth is-danger"  @click="incasare" tip="card" style="margin-top: 10%;">CARD</button>
    </div>
    </div>
</template>
<script>
export default {
    props: [],
    data: function() {
        return {
            email_client: '',
            tip_plata: '',
        }
    },
    methods: {
        incasare: function(event) {
            this.tip_plata = event.target.getAttribute('tip');
            axios.post('casa/incasare', { metoda: this.tip_plata}).then(response => {
              this.$emit("Incasat");

            });
        }
    },

}

</script>
