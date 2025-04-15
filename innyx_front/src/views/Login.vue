<script setup lang="ts">
import { Button, Input, notification } from "ant-design-vue";
import { ref } from "vue";
import router from "../Router/Router";
import axios from "axios";

const username = ref("");
const password = ref("");
const showModal = ref(false);
const loading = ref(false);

const handleLogin = async () => {
  loading.value = true;
  console.log("username:", username.value);
console.log("password:", password.value);

  try {
    const response = await axios.post("http://127.0.0.1:8000/api/login", {
      username: username.value,
      password: password.value,
    });
    console.log(response);
    console.log(username.value);
    console.log(password.value);

    const token = response.data.token;

    localStorage.setItem("token", token);

    notification.success({
      message: "Login realizado com sucesso!",
      description: `Bem-vindo, ${response.data.user.username}`,
    });

    router.push("/painel");
  } catch (error) {
    notification.error({
      message: "Erro ao fazer login",
      description: "Verifique seu usuário e senha e tente novamente.",
    });
  } finally {
    loading.value = false;
  }
};

const handleRegister = () => {
  showModal.value = true;
};

// const handleOk = () => {
//   console.log("Register Username:", registerUsername.value);
//   console.log("Register Password:", registerPassword.value);
//   showModal.value = false;
// };

// const handleCancel = () => {
//   showModal.value = false;
// };
</script>

<template class="container">
  <div class="login">
    <h1>
      <img
        src="https://innyx.com/wp-content/uploads/2023/10/imagem_2023-10-09_115224533.png"
        alt="innyx"
        width="200"
        height="70"
      />
    </h1>
    <div class="form_login">
      <form class="inputs_login" @submit.prevent="handleLogin">
        <Input
          placeholder="Informe seu Usuário"
          v-model:value="username"
          class="input-animation"
        />
        <Input
          placeholder="Informe sua Senha"
          type="password"
          v-model:value="password"
          class="input-animation"
        />
        <Button
          type="default"
          :loading="loading"
          html-type="submit"
          class="button-animation"
        >
          Entrar
        </Button>
      </form>

      <div class="register-link">
        <a @click="handleRegister">
          usuário: usuario_teste <br />
          senha : 123
        </a>
      </div>
    </div>
  </div>

  <!-- <Modal
    class="inputs_login"
    v-model:open="showModal"
    title="Registro de Usuário"
    @ok="handleOk"
    @cancel="handleCancel"
  >
    <Input
      class="input-animation"
      placeholder="Novo Usuário"
      v-model="registerUsername"
      style="margin-bottom: 10px"
    />
    <Input
      class="input-animation"
      placeholder="Nova Senha"
      type="password"
      v-model="registerPassword"
    />
  </Modal> -->
</template>

<style scoped>
body {
  margin: 0;
  padding: 0;
  overflow: hidden !important;
}

.login {
  height: 100vh;
  width: 100vw;
  display: flex;
  justify-content: center;
  align-items: center;
  background: linear-gradient(#400156, #030112, #030112);
  animation: gradient 5s ease infinite;
}

@keyframes gradient {
  0% {
    background-position: 0% 50%;
  }
  50% {
    background-position: 100% 50%;
  }
  100% {
    background-position: 0% 50%;
  }
}

.form_login {
  width: 400px;
  padding: 40px;
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  display: flex;
  flex-direction: column;
  align-items: center;
}

img {
  margin-bottom: 100px;
  margin: 50px;
  max-width: 100%;
  height: auto;
}

.inputs_login {
  display: flex;
  flex-direction: column;
  gap: 20px;
  width: 100%;
}

.inputs_login .ant-input {
  padding: 10px;
  border-radius: 5px;
  transition: border-color 0.3s ease;
}

.inputs_login .ant-input:focus,
.inputs_login .ant-input:hover {
  border-color: #400156;
  box-shadow: 0 0 5px rgba(64, 1, 86, 0.5);
}

.inputs_login .ant-btn {
  height: auto;
  border-radius: 5px;
  font-size: 16px;
}

.register-link {
  margin-top: 20px;
}

.register-link a {
  color: #400156;
  text-decoration: none;
  font-size: 14px;
  cursor: pointer;
}

.button-animation {
  background-color: #400156;
  box-shadow: 0 0 5px rgba(64, 1, 86, 0.5);
  color: white;
  border-color: #400156;
}

.button-animation:hover {
  background-color: #400156;
  box-shadow: 0 0 5px rgba(64, 1, 86, 0.5);
  color: white;
  border-color: #400156;
}

@media (max-width: 768px) {
  .login {
    flex-direction: column;
  }

  .form_login {
    width: 90%;
    padding: 20px;
  }

  img {
    width: 150px;
    height: auto;
    margin: 30px;
  }

  .inputs_login {
    gap: 10px;
  }
}

@media (max-width: 480px) {
  .form_login {
    width: 95%;
    padding: 15px;
  }

  .inputs_login .ant-input,
  .inputs_login .ant-btn {
    padding: 8px;
    font-size: 14px;
  }
}
</style>
