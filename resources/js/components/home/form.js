import Vue from "vue";

Vue.component('home-form', {
  props: {
    home: null
  },
  mounted() {
    console.log(12222222223)

    if (this.home) {
      this.name = this.home.name;
      this.size = this.home.size;
      this.city = this.home.city;
      this.price = this.home.price;
      this.bedrooms_count = this.home.bedrooms_count;
      this.bathrooms_count = this.home.bathrooms_count;
      this.desc = this.home.desc;
      this.sale_agent = this.home.sale_agent;
      this.facs = this.home.facilities;
    }
  },
  data() {
    return {
      name: null,
      size: null,
      city: null,
      price: null,
      bedrooms_count: null,
      bathrooms_count: null,
      desc: null,
      sale_agent: null,
      cover_image: null,
      imgs: null,
      facs:[{
        name: null
      }]
    }
  },
  methods: {
    save() {

      let url = "/homes/create",
          method = 'post';

      if (this.home) {
        url = `/homes/${this.home.id}/edit`
        method = 'put';
      }

      this.saveForm(url, method, '/home', {
        name: this.name,
        size: this.size,
        city: this.city,
        price: this.price,
        bedrooms_count: this.bedrooms_count,
        bathrooms_count: this.bathrooms_count,
        desc: this.desc,
        sale_agent: this.sale_agent,
        cover_image: this.cover_image,
        facs: this.facs,
        imgs: this.imgs
      });
    },
    add() {
      this.facs.push({
        name: null
      });
    },
    remove(index)  {
      this.facs.splice(index, 1)
    }
  },
})