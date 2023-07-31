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
          <div class="mb-3">
            <label for="file" class="form-label">File:</label>
            <input type="file" id="file" @change="onFileChange" />
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
          file: null, // Store the selected file
          
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

          // Method to handle file selection
        onFileChange(event) {
          this.task.file = event.target.files[0];
        },

      async submitForm() {
        try {
          if (this.isNewTask) {
             // Perform file upload if a file is selected
            if (this.task.file) {
            const formData = new FormData();
            formData.append('file', this.task.file);

            // Upload the file to the server (backend) using Laravel's storage functionality
            try {
                const response = await axios.post('/api/tasks/upload-file', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                // Set the file name and path returned from the server to the task object
                this.task.file_name = response.data.file_name;
                this.task.file_path = response.data.file_path;
            } catch (error) {
                console.error('Error uploading file:', error);
            }
        }
            
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