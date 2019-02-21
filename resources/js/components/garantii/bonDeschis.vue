<template>
    <div class="container">
        <ul v-for="product in cart">
            <div class="columns">
                <div class="column">
                    <hr>
                    <li>
                        <figure class="image is-128x128">
                            <img :src="'https://vapez.ro/' +product.img + '-home_default/poza.jpg'">
                            </figure>
                    </li>
                </div>
                <div class="column" style="margin-top:5%;">
                    
                    <li>{{product.name}}</li>
                </div>
                <div class="column" style="margin-top:5%;">
                    
                    <li>{{product.sn}}</li>
                </div>
                <div class="column is-one-third">
                  
                    <li>
                        <div class="field" style="margin-top:15%; margin-right:10%;">
                            <p class="control">
                                <a class="button is-primary"  :id_prod="product.id_prod" :sn="product.sn" @click="primeste">Primeste in garantie</a>
                            </p>
                        </div>
                    </li>
                </div>
            </div>
        </ul>
    </div>
</template>
<script>
export default {
    props: ['cart'],
    methods: {
        primeste: function(event){
            let id_prod = event.target.getAttribute('id_prod');
            let sn = event.target.getAttribute('sn');
   
            this.$emit('primeste',{'id_prod':id_prod,'sn':sn});
        }
    },
    
}

</script>
