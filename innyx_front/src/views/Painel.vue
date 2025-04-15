<script setup lang="ts">
import { ref, computed, h, onMounted, reactive } from "vue";
import {
  Input,
  Table,
  Tag,
  Button,
  Dropdown,
  Menu,
  Modal,
  Form,
  FormItem,
  Select,
  notification,
} from "ant-design-vue";
import type { ColumnsType } from "ant-design-vue/es/table";

import axios from "axios";

interface Product {
  id: number;
  nome: string;
  descricao: string;
  preco: number;
  data_validade: string;
  imagem: string;
  categoria?: {
    id: number;
    nome: string;
  };
}

interface Categoria {
  id: number;
  nome: string;
}

interface ProductForm {
  nome: string;
  descricao: string;
  preco: number;
  data_validade: string;
  imagem: File | null;
  categoria_id: number;
}

const data = ref<Product[]>([]);
const categorias = ref<Categoria[]>([]);
const searchText = ref("");
const isModalVisible = ref(false);
const editingProduct = ref<Product | null>(null);
const newCategoria = ref("");
const form = reactive<ProductForm>({
  nome: "",
  descricao: "",
  preco: 0,
  data_validade: "",
  imagem: null,
  categoria_id: 0,
});
const imagePreview = ref<string | null>(null);

const fetchProdutos = async () => {
  const token = localStorage.getItem("token");
  const response = await axios.get("http://127.0.0.1:8000/api/produtos", {
    headers: { Authorization: `Bearer ${token}` },
  });
  data.value = response.data;
};

const fetchCategorias = async () => {
  const token = localStorage.getItem("token");
  const response = await axios.get("http://127.0.0.1:8000/api/categorias", {
    headers: { Authorization: `Bearer ${token}` },
  });
  categorias.value = response.data;
};

const createCategoria = async () => {
  const token = localStorage.getItem("token");
  if (!newCategoria.value.trim()) return;
  try {
    await axios.post(
      "http://127.0.0.1:8000/api/categorias",
      { nome: newCategoria.value },
      {
        headers: { Authorization: `Bearer ${token}` },
      }
    );
    notification.success({ message: "Categoria criada com sucesso!" });
    newCategoria.value = "";
    await fetchCategorias();
  } catch {
    notification.error({ message: "Erro ao criar categoria." });
  }
};

onMounted(() => {
  fetchProdutos();
  fetchCategorias();
});

const showModal = (record: Product | null = null) => {
  editingProduct.value = record;
  imagePreview.value = null;
  if (record) {
    form.nome = record.nome;
    form.descricao = record.descricao;
    form.preco = record.preco;
    form.data_validade = record.data_validade;
    form.imagem = null;
    form.categoria_id = record.categoria?.id ?? 0;
    if (record.imagem) {
      imagePreview.value = `http://127.0.0.1:8000/storage/${record.imagem}`;
    }
  } else {
    form.nome = "";
    form.descricao = "";
    form.preco = 0;
    form.data_validade = "";
    form.imagem = null;
    form.categoria_id = 0;
  }
  isModalVisible.value = true;
};

const handleImageChange = (e: Event) => {
  const input = e.target as HTMLInputElement;
  if (!input || !input.files?.length) return;
  const file = input.files[0];

  const validTypes = ["image/jpeg", "image/png", "image/jpg"];
  if (!validTypes.includes(file.type)) {
    notification.warning({
      message: "Formato de imagem inválido. Use JPG ou PNG.",
    });
    return;
  }
  if (file.size > 2 * 1024 * 1024) {
    notification.warning({ message: "A imagem deve ter no máximo 2MB." });
    return;
  }

  form.imagem = file;
  const reader = new FileReader();
  reader.onload = () => {
    imagePreview.value = reader.result as string;
  };
  reader.readAsDataURL(file);
};

const submitForm = async () => {
  if (
    !form.nome ||
    !form.descricao ||
    !form.data_validade ||
    !form.categoria_id
  ) {
    return notification.warning({
      message: "Preencha todos os campos obrigatórios.",
    });
  }

  const token = localStorage.getItem("token");
  const formData = new FormData();
  Object.entries(form).forEach(([key, value]) => {
    if (value !== null && value !== undefined) {
      if (key === "imagem" && value instanceof File) {
        formData.append(key, value);
      } else {
        formData.append(key, String(value));
      }
    }
  });
  try {
    if (editingProduct.value) {
      await axios.post(
        `http://127.0.0.1:8000/api/produtos/${editingProduct.value.id}?_method=PUT`,
        formData,
        {
          headers: {
            Authorization: `Bearer ${token}`,
            "Content-Type": "multipart/form-data",
          },
        }
      );
    } else {
      await axios.post("http://127.0.0.1:8000/api/produtos", formData, {
        headers: {
          Authorization: `Bearer ${token}`,
          "Content-Type": "multipart/form-data",
        },
      });
    }
    notification.success({ message: "Produto salvo com sucesso!" });
    isModalVisible.value = false;
    fetchProdutos();
  } catch {
    notification.error({ message: "Erro ao salvar produto." });
  }
};

const handleDelete = async (id: number) => {
  const token = localStorage.getItem("token");
  try {
    await axios.delete(`http://127.0.0.1:8000/api/produtos/${id}`, {
      headers: { Authorization: `Bearer ${token}` },
    });
    notification.success({ message: "Produto excluído com sucesso!" });
    fetchProdutos();
  } catch {
    notification.error({ message: "Erro ao excluir produto." });
  }
};

const filteredData = computed(() => {
  const search = searchText.value.toLowerCase();
  return data.value.filter(
    (item) =>
      item.nome.toLowerCase().includes(search) ||
      item.descricao.toLowerCase().includes(search) ||
      item.categoria?.nome.toLowerCase().includes(search)
  );
});

const columns: ColumnsType<Product> = [
  { title: "Nome", dataIndex: "nome", key: "nome" },
  { title: "Descrição", dataIndex: "descricao", key: "descricao" },
  {
    title: "Preço",
    dataIndex: "preco",
    key: "preco",
    customRender: ({ text }) => `R$ ${parseFloat(text).toFixed(2)}`,
  },
  {
    title: "Data de Validade",
    dataIndex: "data_validade",
    key: "data_validade",
  },
  {
    title: "Imagem",
    dataIndex: "imagem",
    key: "imagem",
    customRender: ({ text }) =>
      h("img", {
        src: `http://127.0.0.1:8000/storage/${text}`,
        style: "width: 60px; height: 60px; border-radius: 8px;",
      }),
  },
  {
    title: "Categoria",
    key: "categoria",
    customRender: ({ record }) =>
      h(
        Tag,
        { color: "blue" },
        () => record.categoria?.nome ?? "Sem categoria"
      ),
  },
  {
    title: "Ações",
    key: "acoes",
    customRender: ({ record }) =>
      h(
        Dropdown,
        {
          overlay: h(
            Menu,
            {},
            {
              default: () => [
                h(
                  Menu.Item,
                  { key: "1", onClick: () => showModal(record) },
                  () => "Editar"
                ),
                h(
                  Menu.Item,
                  {
                    key: "2",
                    danger: true,
                    onClick: () => handleDelete(record.id),
                  },
                  () => "Excluir"
                ),
              ],
            }
          ),
        },
        {
          default: () => h(Button, {}, { default: () => "Mais" }),
        }
      ),
  },
];
</script>

<template>
  <div class="page-background">
    <div class="header">
      <h2 class="title">Produtos</h2>
      <img
        src="https://innyx.com/wp-content/uploads/2023/10/imagem_2023-10-09_115224533.png"
        alt="innyx"
        class="logo"
      />
    </div>

    <div class="container">
      <Button
        type="primary"
        @click="() => showModal(null)"
        style="margin-bottom: 20px"
      >
        Novo Produto
      </Button>

      <Input
        v-model:value="searchText"
        placeholder="Buscar por Nome, Descrição ou Categoria"
        allowClear
        size="large"
        type="search"
        class="search-input animated-input"
      />

      <Table
        :columns="columns"
        :data-source="filteredData"
        :pagination="{ pageSize: 5 }"
        size="middle"
        class="product-table"
      />

      <Modal
        v-model:visible="isModalVisible"
        title="Produto"
        @ok="submitForm"
        @cancel="() => (isModalVisible = false)"
      >
        <Form layout="vertical">
          <FormItem label="Nome">
            <Input v-model:value="form.nome" />
          </FormItem>
          <FormItem label="Descrição">
            <Input v-model:value="form.descricao" />
          </FormItem>
          <FormItem label="Preço">
            <Input v-model:value="form.preco" type="number" />
          </FormItem>
          <FormItem label="Data de Validade">
            <Input v-model:value="form.data_validade" type="date" />
          </FormItem>
          <FormItem label="Categoria">
            <Select
              v-model:value="form.categoria_id"
              placeholder="Selecione uma categoria"
            >
              <Select.Option
                v-for="cat in categorias"
                :key="cat.id"
                :value="cat.id"
                >{{ cat.nome }}</Select.Option
              >
            </Select>
          </FormItem>
          <FormItem label="Imagem">
            <input type="file" @change="handleImageChange" />
            <div v-if="imagePreview" style="margin-top: 10px">
              <img
                :src="imagePreview"
                style="max-width: 100px; border-radius: 8px"
              />
            </div>
          </FormItem>
        </Form>
      </Modal>
    </div>
  </div>
</template>

<style scoped>
.page-background {
  background: linear-gradient(135deg, #e3f2fd, #fce4ec);
  min-height: 100vh;
  padding: 30px 20px;
  box-sizing: border-box;
  overflow: scroll;
}

.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 30px;
  padding: 0 10px;
}

.title {
  font-size: 32px;
  font-weight: bold;
  color: #333;
  font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
}

.logo {
  height: 60px;
  max-width: 200px;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.logo:hover {
  transform: scale(1.05);
}

.container {
  padding: 30px 20px;
  max-width: 1200px;
  margin: 0 auto;
  background-color: #ffffffdd;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
  display: flex;
  flex-direction: column;
  align-items: center;
}

.search-input {
  width: 100%;
  max-width: 500px;
  margin-bottom: 25px;
  transition: all 0.3s ease;
}

.animated-input:focus-within {
  transform: scale(1.02);
  box-shadow: 0 0 0 2px #1890ff44;
}

.ant-input-affix-wrapper {
  border-radius: 12px;
  transition: border-color 0.3s, box-shadow 0.3s;
}

.ant-input-affix-wrapper:hover {
  border-color: #1890ff;
  box-shadow: 0 0 5px rgba(24, 144, 255, 0.3);
}

.product-table {
  width: 100%;
  overflow-x: auto;
}

/* Responsivo */
@media (max-width: 768px) {
  .header {
    flex-direction: column;
    align-items: flex-start;
    gap: 10px;
  }

  .title {
    font-size: 24px;
  }

  .logo {
    align-self: flex-end;
    max-width: 150px;
    height: auto;
  }
}
</style>
