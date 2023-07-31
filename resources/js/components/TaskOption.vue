<template>
    <div>
      <h2 v-if="isNewTask">New Task</h2>
      <h2 v-else>Edit Task</h2>
        <form @submit.prevent="submitForm">
          <div class="mb-3">
            <label for="title" class="form-label">Title:</label>
            <input class="form-control" type="text" id="title" v-model="task.title" required />
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" id="description" v-model="task.description" required></textarea>
          </div>
          <div class="mb-3">
            <label for="status" class="form-label">Status:</label>
            <select class="form-select" aria-level="Select status" id="status" v-model="task.status" required>
                <option value="Pending" selected>Pending</option>
                <option value="Ongoing">Ongoing</option>
                <option value="Done">Completed</option>
            </select>
          </div>
          <button type="submit" v-if="isNewTask" class="btn btn-primary">Add Task</button>
          <button type="submit" v-else class="btn btn-primary">Update Task</button>
        </form>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        task: {
          title: '',
          description: '',
          status: '',
        }
      }
    },
    computed: {
      isNewTask() {
        return !this.$route.path.includes('edit');
      }
    },
    async created() {
      if (!this.isNewTask) {
        const response = await axios.get(`/api/tasks/${this.$route.params.id}`);
        this.task = response.data;
      }
    },
    methods: {
      async submitForm() {
        try {
          if (this.isNewTask) {
            await axios.post('/api/tasks', this.task);
          } else {
            await axios.put(`/api/tasks/${this.$route.params.id}`, this.task);
          }
          this.$router.push('/');
        } catch (error) {
          console.error(error.response.data);
        }
      }
    }
  }
  </script>