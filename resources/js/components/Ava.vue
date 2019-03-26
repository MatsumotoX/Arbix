<template>
  <section id="ava" class="cd-section cd-background-ava" v-if="isReady">
    <div class="content-wrapper">
      <div class="container align-middle">
        <div class>
          <img
            class="img-fluid text-center mt-5 mb-3"
            style="width: 200px;"
            alt
            src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/AVA-Advisor-Logo.png"
          >
          <br>
          <span class="section-divider"></span>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="align-middle">
              <div>
                <div class="mt-5 mb-5">
                  <h1>เราคือ
                    <br>ผู้เชี่ยวชาญในด้าน Speculative Trading
                  </h1>
                  <p>ที่ครอบครองส่วนแบ่งตลาก Robo Advisory Platform อันดับ 1 ในประเทศไทย</p>
                </div>
              </div>
              <div class="mt-5 mb-5">
                <img
                  class="img-fluid"
                  alt="Broker"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Broker.jpg"
                >
              </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-12" style="text-align:left;">
            <div class="mt-5 mb-5">
              <h1>About Us</h1>
              <p>AVA เดิมชื่อบริษัท MarketAnyware ซึ่งทำ Tool ให้กับ Broker รายใหญ่ อย่างหลักทรัพย์กสิกร หลักทรัพย์ไทยพาณิชย์ แล้วภายหลังเปลี่ยนชื่อเป็น AVA
                <br>ผลงานที่ผ่านมา: AVA Advisor, AVA Alpha and AVARan (Broker)
                <br>จกทะเบียนบริษัท: Market Anyware Company Limited
                <br>ก่อตั้งเมื่อปี: 2013
              </p>
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


