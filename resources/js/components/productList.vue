<template>
       
            <table class="table">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="product in products">
                        <td><button class="button is-primary">Info</button></td>
                        <td>[38]</td>
                        <td>23</td>
                        <td><button class="button is-primary">Cos</button></td>
                        <td style="padding-left:100px;width:20%;"> {{product.nume}}</td>
                        <td colspan="5" style="padding-left:100px;"><button class="button is-primary is-fullwidth is-block" :id_prod="product.id_prod" @click="addToCart">{{product.pret}} lei</button></td>
                    </tr>
                </tbody>
            </table>
      
</template>
<script>
export default {

    props: ['products'],

    data: function() {
        return {
    		
        }
    },
    methods: {
    	

        addToCart: function(event) {
            let id_prod = event.target.getAttribute('id_prod');
            axios.get('/casa/cart_content/' + id_prod).then(response => {
                  
               
          
            });
             this.$emit('add');
        },
       

}
}
</script>
