<template>
<div class="container is-fluid">
  <div class="columns">
    <div class="colum">
      <categ-menu @getProd="getProdList($event)"
                  @search="search($event)"
                  @showChanged="show = $event"
                  :menu="menu"></categ-menu>
    </div>
    <product-list v-if="show == 'products'"
                  :products="products"></product-list>
    <div class="column is-two-fifths is-offset-two-fifths"
         v-if="show == 'cash-card'">
      <cash-card @Incasat="showCh2('cart')"
                 class="column"></cash-card>
    </div>
    <cart @showChanged="showCh($event)"
          :cart="cart"
          v-if="show == 'cart'"></cart>
    <div class="column is-half is-offset-3">
      <date-client @Primit="showCh2('cash-card')"
                   v-if="show == 'date-client'"></date-client>
    </div>
  </div>
  <nav class="level"
       style="margin-top: 5%;">
    <div v-if="reduceri['99998']" class="level-item has-text-centered">
      <div>
        <p class="heading">Reducere TAG</p>
        <p class="title">{{reduceri['99998'].pret}}</p>
      </div>
    </div>
    <div v-if="reduceri['99995']" class="level-item has-text-centered">
      <div>
        <p class="heading">Reducere casa</p>
        <p class="title">{{reduceri['99995'].pret}}</p>
      </div>
    </div>
    <div v-if="reduceri['99997']" class="level-item has-text-centered">
      <div>
        <p class="heading">Reducere rotunjire</p>
        <p class="title">{{reduceri['99997'].pret}}</p>
      </div>
    </div>
  </nav>
</div>
</template>
<script>
export default
{
  props: [],
  data: function()
  {
    return {
      show: 'cart',
      products: [],
      menu: [],
      cart: [],
      reduceri: [],

    }
  },
  methods:
  {
    showCh: function(event)
    {
      this.show = event;

    },
    showCh2: function(event)
    {
      axios.get('/casa/showcart').then(response =>
      {
        this.cart = response.data.produse;
        this.reduceri = response.data.reduceri;
      });
      this.show = event;

    },
    getProdList: function(event)
    {

      axios.get('/casa/lista_produse/' + event).then(response =>
      {
        this.products = response.data.products;
        this.show = 'products';

      });

    },
    search: function(event)
    {
      axios.get('/casa/search/' + event).then(response =>
      {
        this.products = response.data;

        this.show = 'products';

      });
    },

  },
  created()
  {
    axios.get('/casa/sidebar_categ/1').then(response =>
    {
      this.menu = response.data;
    });
    axios.get('/casa/showcart').then(response =>
    {
      this.cart = response.data.produse;
      this.reduceri = response.data.reduceri;
    });

  }

}

</script>
