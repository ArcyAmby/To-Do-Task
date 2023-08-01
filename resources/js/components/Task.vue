<template>
    <div>
        <h5 id="associating-form-text-with-form-controls">Title:</h5>
        <h6>{{ task.title }}</h6>
        <h5 id="associating-form-text-with-form-controls">Description:</h5>
        <p>{{ task.description }}</p>
        <h5 id="associating-form-text-with-form-controls">Status:</h5>
        <p>Status: {{ task.status }}</p>
        <div v-if="task.file_path">
            <h5>File:</h5>
            <div v-if="isImage(task.file_path)">
            <img :src="task.file_path" alt="File Preview" style="max-width: 400px;" />
            </div>
            <div v-else-if="isPDF(task.file_path)">
            <iframe :src="task.file_path" style="width: 100%; height: 500px;"></iframe>
            </div>
        </div>


        <router-link :to="`/tasks/${task.id}/edit`" class="btn btn-primary">Edit</router-link>
    </div>
</template>

<script>
import axios from 'axios';

export default {
data() {
    return {
    task: {}
    }
},
async created() {
    try {
    const response = await axios.get(`/api/tasks/${this.$route.params.id}`);
    this.task = response.data;
    } catch (error) {
    console.error(error);
    }
},
methods: {
    isImage(filePath) {
      const imageExtensions = ['.png', '.jpg', '.jpeg', '.gif'];
      return imageExtensions.some((ext) => filePath.toLowerCase().endsWith(ext));
    },
    isPDF(filePath) {
      return filePath.toLowerCase().endsWith('.pdf');
    },
  },
};
</script>