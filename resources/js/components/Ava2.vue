<template>
  <section id="experiances" class="cd-section cd-background-ava" v-if="isReady">
    <div class="content-wrapper">
      <div class="container align-middle">
        <div class="mt-5">
          <h1 style="font-size: 6rem;">Fusion</h1>
          <br>
          <span class="section-divider"></span>
        </div>
        <div class="row">
          <div class="col-md-8 col-sm-12">
            <div class="row">
              <div class="col">
                <div>
                  <div class="mt-5 mb-5">
                    <img
                      class="img-fluid rounded-circle x-border"
                      style="width:80%"
                      src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Pondet.jpg"
                    >
                    <img
                      class="img-fluid rounded-circle x-topic-border x-topic"
                      src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Digital-Asset-Arbitrage.png"
                    >
                  </div>
                  <br>
                  <div class="mt-2 mb-2">
                    <p>Pondet Ananchai</p>
                    <img
                      class="img-fluid"
                      style="width:150px"
                      alt="CryptovationX"
                      src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/(Main)+CryptovationX_C-Gradient_Text-Black.png"
                    >
                  </div>
                </div>
              </div>
              <div class="col">
                <img
                  class="img-fluid"
                  style
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi1.png"
                >
                <h1>Arbi Bot</h1>
                <p>"โรบอทที่ช่วยในการทำ Arbitrage ในสินทรพย์ทุกประเภท"</p>
              </div>
              <div class="col">
                <div>
                  <div class="mt-5 mb-5">
                    <img
                      class="img-fluid rounded-circle ava-border"
                      style="width:80%"
                      src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Niran.jpg"
                    >
                    <img
                      class="img-fluid rounded-circle ava-topic-border x-topic"
                      src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Traditional-Asset-Arbitrage.png"
                    >
                  </div>
                  <br>
                  <div>
                    <p>Niran Pravithana</p>
                    <img
                      class="img-fluid"
                      style="width:150px"
                      alt="AVA Advisor"
                      src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/partners/Ava.png"
                    >
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-12">
            <div class="row">
              <div class="col">
                <img
                  class="img-fluid rounded-circle ava-border"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/AVA-SET.jpg"
                >
                <img
                  class="img-fluid rounded-circle ava-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/SET-Logo.jpg"
                >
              </div>
              <div class="col" style="text-align: left;
    margin: 20px 0px;">
                <h1>รางวัลชนะเลิศ</h1>
                <p>สุดยอดนวัตกรรมตลาดหุ้นไทย 2561</p>
              </div>
            </div>
            <div class="row">
              <div class="col" style="text-align: right;
    margin: 20px 0px;">
                <h1>รางวัลชนะเลิศ</h1>
                <p>"TFEX Algorithmic Traing Workshop & Competition 2018"</p>
              </div>
              <div class="col">
                <img
                  class="img-fluid rounded-circle ava-border"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/AVA-TFEX.jpg"
                >
                <img
                  class="img-fluid rounded-circle ava-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/TFEX-Logo.jpg"
                >
              </div>
            </div>
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
.cover-image {
  width: 400px;
}
</style>


