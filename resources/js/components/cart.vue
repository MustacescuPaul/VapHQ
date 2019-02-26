<template>
    <div id="products" style="margin-left: 35%;width: 60%; overflow-x: visible;position:fixed;">
        <table class="table is-fullwidth">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="product in cart">
                    <td>
                        <figure class="image is-128x128">
                            <img :src="'https://vapez.ro/' +product.img + '-home_default/poza.jpg'">
                            </figure>
                    </td>
                    <td>{{product.nume}}</td>
                    <td>{{product.pret}} lei</td>
                    <td>
                        <div class="field is-grouped">
                            <p class="control">
                                <a class="button is-primary" :id_prod="product.id_prod" @click="increaseQ">+</a>
                                <div style="width:50px;margin-right:12px;">
                                    <input type="text" class="input" :value="product.cantitate">
                                </div>
                                <a class="button is-danger" :id_prod="product.id_prod" @click="decreaseQ">-</a>
                            </p>
                        </div>
                    </td>
                    <td><input type="text" class="input" required :id_prod="product.id_prod" v-model="product.sn" @blur="serial" v-if="product.sn != 0">
                    <p class="help is-danger">{{errors.serial}}</p> </td>
                </tr>
                 
            </tbody>
        </table>
        <button class="button is-success is-fullwidth" :disabled="bOk == 0" @click="bon">Bon {{total - val_reducere}}</button>
        <input type="number" class="input" :max="r" v-model="val_reducere" style="width: 20%;margin-top: 1%;" placeholder="Reducere" v-on:keyup.enter="reducere">
        <button class="button" style="width: 20%;margin-top: 1%;" id_tag="080047299BFD" @click="reducereTag">Reducere TAG</button>
    </div>
</template>
<script>
export default {
    props: ['cart'],
    data: function() {
        return {
            bOk: 1,
            val_reducere: 0,
            total: '',
            r: 10,
            errors: [],

        }
    },
    methods: {
        reducereTag: function(event) {
            let id_tag =   event.target.getAttribute('id_tag');
                
                axios.post('/casa/incasare', { id_tag: id_tag }).then(response => {


                });
            
        },
        reducere: function(event) {
            if (this.val_reducere <= this.total) {
                axios.post('/casa/incasare', { reducere: this.val_reducere }).then(response => {


                });
            }
        },
        serial: function(event) {

            let id_prod = event.target.getAttribute('id_prod');
            let serial = event.target.value;

             axios.post('/casa/savesn', { id_prod: id_prod, serial:serial }).then(response => {

            }).catch(error => {

                this.errors = error.response.data.errors;

            });

        },
        increaseQ: function(event) {
            let id_prod = event.target.getAttribute('id_prod');
            axios.get('/casa/increase_q/' + id_prod).then(response => {
                for (let item of this.cart) {
                    if (item['id_prod'] == id_prod)
                        item['cantitate'] = response.data;
                }
            });


        },
        decreaseQ: function(event) {
            let id_prod = event.target.getAttribute('id_prod');

            axios.get('/casa/decrease_q/' + id_prod).then(response => {
                let i = 0;
                if (response.data == 0)
                    for (let item of this.cart) {
                        if (item['id_prod'] == id_prod)
                            this.cart.splice(i, 1);
                        i++;

                    }
                for (let item of this.cart) {
                    if (item['id_prod'] == id_prod)
                        item['cantitate'] = response.data;
                }

            });
            if (this.cart.length < 1) {

                this.bOk = 0;
              
            }
        },
        bon: function(event) {
            let temp = 1;
            for (let item of this.cart) {
                if (item['sn'] > 0) {
                    temp = 0;

                }
            }
            if (temp == 1)
                this.$emit('showChanged', 'cash-card');
            else
                this.$emit('showChanged', 'date-client');

        },

    },
    updated: function(event) {
        let temp = 1;
        let tot = 0;
        if (this.cart.length > 0) {
            for (let item of this.cart) {
                tot = Number(tot) + Number(item['pret']) * Number(item['cantitate']);

                if (item['sn'] == 1) {
                    temp = 0;

                }
            }
            this.bOk = temp;
        } else {
            this.bOk = 0;
        }

        axios.post('/casa/reducere_tot', { pret: tot }).then(response => {

            this.total = tot + Number(response.data);
        });

    
      
    }
}

</script>
