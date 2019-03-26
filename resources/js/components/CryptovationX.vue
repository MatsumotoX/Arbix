<template>
  <section id="cryptovationx" class="cd-section cd-background-cryptovationx" v-if="isReady">
    <div class="content-wrapper">
      <div class="container align-middle">
        <div class>
          <img
            class="img-fluid text-center mt-5 mb-3"
            style="width: 400px;"
            alt
            src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/(Main)+CryptovationX_C-Gradient_Text-Black.png"
          >
          <br>
          <span class="section-divider"></span>
        </div>
        <div class="row">
          <div class="col-md-6 col-sm-12" style="text-align:left;">
            <div class="mt-5 mb-5">
              <h1>Our Vision</h1>
              <p>"การใช้ประโยชน์จากเทคโนโลยี A.I. เพื่อให้บริการหุ่นยยต์ให้คำปรึกษาแบบครบวงจร เพื่อสร้างความั่งคั่งให้ทุกคนด้วยแนวคิด Wealth for All"</p>
            </div>
            <div class="mt-5 mb-5">
              <h1>About Us</h1>
              <p>CryptovationX เป็นบริษัทในเครือ Cryptovation.co ซึ่งเป็นที่ปรึกษาทางด้านการลงทุนในสกุลเงินดิจิทัล ซึ่งเราทำ Arbot Terminal สำหรับให้คนทั่วไปได้ใช้
                <br>บริษัทอยู่ที่: อาคาร Knowledge Exchange
                <br>จกทะเบียนบริษัท: ประเทศสิงคโปร์
                <br>ก่อตั้งเมื่อปี: 2017
                <br>สมาชิกในทีม: 11-30 คน
              </p>
            </div>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="align-middle">
              <div>
                <div class="mt-5 mb-5">
                  <h1>Project
                    <br>Arbot Terminal
                  </h1>
                </div>
              </div>
              <div class="mt-5 mb-5">
                <img
                  class="img-fluid"
                  alt="Arbot"
                  src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/Arbi/Arbot+Terminal.png"
                >
              </div>By
              <img
                class="img-fluid"
                alt="Cryptovation.co-logo"
                src="https://s3-ap-southeast-1.amazonaws.com/cryptovationx/public/logos/cryptovationco.png"
              >
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


