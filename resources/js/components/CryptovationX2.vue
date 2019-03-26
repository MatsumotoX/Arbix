<template>
  <section id="experiances" class="cd-section cd-background-cryptovationx" v-if="isReady">
    <div class="content-wrapper">
      <div class="container align-middle">
        <div class="mt-5">
          <h1 style="font-size: 6rem;">Experiances</h1>
          <br>
          <span class="section-divider"></span>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-12">
            <div class="row">
              <div class="col">
                <img
                  class="img-fluid rounded-circle x-border"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Fintech-Challenge.jpg"
                >
                <img
                  class="img-fluid rounded-circle x-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/SEC-Logo.jpg"
                >
                <img
                  class="img-fluid rounded-circle x-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/FintechChallenge-Logo.jpg"
                >
              </div>
              <div class="col" style="text-align: left;
    margin: 40px 0px;">
                <h1>Fintech Challenge ปี 2017 และ 2018</h1>
                <p>เข้ารอบ 10 ทีม และ 12 ทีมสุดท้าย</p>
              </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="row">
              <div class="col" style="text-align: right;
    margin: 40px 0px;">
                <h1>รางวัลรองชนะเลิศ</h1>
                <p>Krungsri Rise Demo day</p>
              </div>
              <div class="col">
                <img
                  class="img-fluid rounded-circle x-border"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Krungsri-RISE.jpg"
                >
                <img
                  class="img-fluid rounded-circle x-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Krungsri-Bank-Logo.jpg"
                >
                <img
                  class="img-fluid rounded-circle x-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Krungsri-Finnovate-Logo.jpg"
                >
              </div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-8 col-sm-12">
            <div class="row">
              <div class="col">
                <h1>Speaker</h1>
                <p>ผลงานวิชาการต่างๆ</p>
                <img
                  class="img-fluid rounded-circle x-border"
                  style="width:80%"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Speak-UOB.jpg"
                >
                <img
                  class="img-fluid rounded-circle x-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/UOB-Logo.jpg"
                >
              </div>
              <div class="col">
                <img
                  class="img-fluid rounded-circle x-border"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/SET-Speaker.jpg"
                >
                <img
                  class="img-fluid rounded-circle x-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/SET-Logo.jpg"
                >
                <img
                  class="img-fluid rounded-circle x-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/TFTA-Logo.jpg"
                >
                <img
                  class="img-fluid rounded-circle x-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Singha-Logo.jpg"
                >
              </div>
            </div>
          </div>
          <div class="col-md-4 col-sm-12">
            <div class="row">
              <div class="col">
                <img
                  class="img-fluid rounded-circle x-border"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/TNN.jpg"
                >
                <img
                  class="img-fluid rounded-circle x-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/TNN-Logo.jpg"
                >
              </div>
              <div class="col" style="text-align: left;
    margin: 20px 0px;">
                <h1>เจาะข่าวลึก</h1>
                <p>มองรอบด้านลงทุน "เงินดิจิทัล" กับ ดร.ภูมิ ภูมิรัตน</p>
              </div>
            </div>
            <div class="row">
              <div class="col" style="text-align: right;
    margin: 20px 0px;">
                <h1>B Time</h1>
                <p>CryptovationX เหรียญดิจิทัลเพื่อหุ่นยนต์ที่ปรึกษาสำหรับนักลงทุน 4.0</p>
              </div>
              <div class="col">
                <img
                  class="img-fluid rounded-circle x-border"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/B-Times.jpg"
                >
                <img
                  class="img-fluid rounded-circle x-topic-border x-topic"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/3-Family-Logo.jpg"
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


