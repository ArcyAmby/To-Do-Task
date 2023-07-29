<template>
    <div>
        <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Status</th>
                <th scope="col">Actions</th>
              </tr>
            </thead>
            <tbody>
                <tr v-for="task in tasks" :key="task.id">
                    <td>{{ task.id }}</td>
                    <td>{{ task.title }}</td>
                    <td>{{ task.description }}</td>
                    <td>{{ task.status }}</td>
                    <td>
                      <div class="row gap-3">
                        <router-link :to="`/tasks/${task.id}`" class="p-2 col border btn btn-primary">View</router-link>
                        <router-link :to="`/tasks/${task.id}/edit`" class="p-2 col border btn btn-success">Edit</router-link>
                        <button @click="deleteTask(task.id)" type="button" class="p-2 col border btn btn-danger">Delete</button>
                      </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
  data() {
    return {
      tasks: []
    }
  },
  async created() {
    try {
      const response = await axios.get('/api/tasks');
      this.tasks = response.data;
    } catch (error) {
      console.error(error);
    }
  },
  methods: {
    async deleteTask(id) {
      try {
        await axios.delete(`/api/tasks/${id}`);
        this.tasks = this.tasks.filter(task => task.id !== id);
      } catch (error) {
        console.error(error);
      }
    }
  }
}
</script>
