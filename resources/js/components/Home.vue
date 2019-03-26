<template>
  <section id="home" class="cd-section cd-background-home" v-if="isReady">
    <div class="content-wrapper">
      <div class="container">
        <p @click="changeLanguage" class="fix-area" style="color: white;">EN/TH</p>
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <img
              src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi1.png"
              alt="Arbi"
            >
            <h1 style="color: white;">Arbi Bot</h1>
            <br>By
            <img src="img/svg/CXA_Logo.svg" alt="CryptovationX-Logo">
          </div>
          <div class="col-md-6 col-sm-12">
            <h1 style="color: white;">{{ data.header }}</h1>
            <p style="color: white;">{{ data.content }}</p>
            <img class="img-fluid" alt="Responsive image" :src="data.image1">
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
export default {
  data() {
    return {
      data: null,
      isReady: false,
      language: "EN",
      showProduct: false
    };
  },

  created() {
    this.getData();
  },

  methods: {
    getData() {
      return new Promise((resolve, reject) => {
        axios
          .get("/landingpage/home")
          .then(response => {
            this.data = response.data;
            this.isReady = true;
            console.log(response.data);
            resolve(response.data);
          })
          .catch(error => {
            console.log(error);
            reject(error.response);
          });
      });
    },

    changeLanguage() {
      switch (this.language) {
        case "EN":
          this.language = "TH";
          break;
        case "TH":
          this.language = "EN";
          break;
      }

      for (let property in this.data) {
        if (property.search("_") == -1) {
          this.data[property] = this.data[property + "_" + this.language];
        }
      }
    }
  }
};
</script>

<style>
/* .fix-area {
  top: 10px;
  color: #fff;
  cursor: pointer;
  font-size: 40px;
  padding: 12px 14px;
  position: fixed;
  right: 0px;
} */

.cover-image {
  width: 400px;
}
</style>


