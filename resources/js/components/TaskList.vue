<template>
    <div>
        <table class="table">
            <thead>
              <tr>
                <th scope="col">Task #</th>
                <th scope="col">Title</th>
                <th scope="col">Description</th>
                <th scope="col">Status</th>
                <th scope="col">Uploaded File</th>
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
                      <!-- Show the file name if available -->
                      <span v-if="task.file_name">
                          {{ task.file_name }}
                          <!-- Provide a link to download or view the file -->
                          <a :href="task.file_path" target="_blank">Download</a>
                      </span>
                      <span v-else>No File Uploaded</span>
                  </td>
                    <td>
                      <div class="row gap-3">
                        <router-link :to="`/tasks/${task.id}`" :style="{ backgroundColor: buttonColor }" class="p-2 col border btn "><i class="fas fa-eye"></i></router-link>
                        <router-link :to="`/tasks/${task.id}/edit`" :style="{ backgroundColor: buttonColor }" class="p-2 col border btn "><i class="fas fa-pencil-alt"></i></router-link>
                        <button @click="showModal = true" type="button" :style="{ backgroundColor: buttonColor }" class="p-2 col border "><i class="fas fa-trash"></i></button>
                        <div v-if="showModal" class="modal">
                          <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                              <div class="modal-header text-white">
                                <h3 class="modal-title">Confirm Deletion</h3>
                                <button type="button" class="close" @click="showModal = false">&times;</button>
                              </div>
                              <div class="modal-body">
                                <p>Are you sure you want to delete this task?</p>
                              </div>
                              <div class="modal-footer">
                                <button @click="deleteTask(task.id)" type="button" class="btn btn-danger">Yes</button>
                                <button type="button" class="btn btn-secondary" @click="showModal = false">Cancel</button>
                              </div>
                            </div>
                          </div>
                        </div>
                        
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
  props: ['task'],
  data() {
    return {
      tasks: [],
      showModal: false,
      buttonColor: '#F2AA4CFF'
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
        // Call the delete endpoint using Axios
        await axios.delete(`/api/tasks/${id}`);
        // Handle success (e.g., show a success message, update the list, etc.)
        this.tasks = this.tasks.filter(task => task.id !== id);
        console.log('Task deleted successfully');
        this.showModal = false;
        // Reload the list or update Vue data as needed
      } catch (error) {
        // Handle error (e.g., show an error message)
        console.error('Error deleting task:', error);
        this.showModal = false;
      }
    }
  }
}
</script>
<style>
/* Style the modal */

:root {
  --primary-color: #101820FF;
  --secondary-color: #F2AA4CFF;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
}

.modal-dialog {
  max-width: 400px;
  width: 90%;
}

/* Optional - Modify the modal header background color and text color */
.modal-header {
  background-color: var(--secondary-color);
  color: #fff;
}

/* Optional - Center the modal body text */
.modal-body {
  text-align: center;
}

/* Optional - Add some spacing between buttons in the modal footer */
.modal-footer button {
  background-color: var(--secondary-color);
  margin-right: 10px;
  border-style: none;
}
</style>