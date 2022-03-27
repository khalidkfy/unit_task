import Vue from "vue";

Vue.component('home-index', {
  mounted() {
    this.$on('homeDeleted', () => {
      this.refreshTable();
    })
  },
  data() {
    return {
      city:null,
      desc:null
    }
  },
  methods: {
    async fetchData({page, sort}) {
      const response = await axios.get("/homes/data", {
        params: {
          page,
          sort,
          city: this.city,
          desc: this.desc,
        }
      });
      return {
        data: response.data.homes,
        pagination: {
          currentPage: response.data.homes.current_page,
          totalPages: response.data.homes.last_page
        }
      };
    },
    refreshTable() {
      this.$refs.table.refresh()
    },
  },
})