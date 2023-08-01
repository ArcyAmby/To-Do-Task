<template>
  <div>
    <h2 v-if="isNewTask"></h2>
    <h2 v-else></h2>
    <form @submit.prevent="submitForm" class="modal-form">
      <div class="container">
        <div class="row mb-3">
          <label for="title" class="col-sm-2 col-form-label">Title:</label>
          <div class="col-sm-12">
            <input class="form-control" type="text" id="title" v-model="task.title" required />
          </div>
        </div>
        <div class="row mb-3">
          <label for="description" class="col-sm-2 col-form-label">Description:</label>
          <div class="col-sm-12">
            <textarea class="form-control" id="description" v-model="task.description" required></textarea>
          </div>
        </div>
        <div class="row mb-3">
          <label class="col-sm-2 col-form-label">Status:</label>
          <div class="col-sm-10">
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="pending" value="Pending" v-model="task.status" required>
              <label class="form-check-label" for="pending">Pending</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="ongoing" value="Ongoing" v-model="task.status" required>
              <label class="form-check-label" for="ongoing">Ongoing</label>
            </div>
            <div class="form-check form-check-inline">
              <input class="form-check-input" type="radio" id="completed" value="Completed" v-model="task.status" required>
              <label class="form-check-label" for="completed">Completed</label>
            </div>
          </div>
        </div>
        <div class="row mb-3">
          <label for="file" class="col-sm-2 col-form-label">File:</label>
          <div class="col-sm-12">
            <input type="file" id="file" @change="onFileChange" accept=".pdf" />
          </div>
        </div>
        <div class="row">
          <div class="col-sm-2"></div>
          <div class="col-sm-12">
            <button type="submit" class="btn btn-primary" v-if="isNewTask">Add Task</button>
            
          </div>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import axios from 'axios';

export default {
  props: {
    task: Object,
    isNewTask: Boolean,
  },
  data() {
    return {
      buttonColor: '#F2AA4CFF',
      taskData: {}
    };
  },
  created() {
    
    console.log(this.task);
    this.taskData = { ...this.task };
  },
  methods: {
    
    onFileChange(event) {
      this.task.file = event.target.files[0];
    },
    async submitForm() {
      try {
        if (this.isNewTask) {
          
          if (this.task.file) {
            const formData = new FormData();
            formData.append('file', this.task.file);

            
            try {
              const response = await axios.post('/api/tasks/upload-file', formData, {
                headers: {
                  'Content-Type': 'multipart/form-data'
                }
              });

              this.task.file_name = response.data.file_name;
              this.task.file_path = response.data.file_path;
            } catch (error) {
              console.error('Error uploading file:', error);
            }
          }

          await axios.post('/api/tasks', this.task);

          this.$emit('new-task-created', this.taskData);
          
          this.task.title = '';
          this.task.description = '';
          this.task.status = '';
          this.task.file = null;

        } else {
          await axios.put(`/api/tasks/${this.task.id}`, this.task);
          // const response = await axios.get(`/api/tasks/${this.task.id}`);
          // this.task = response.data;
        }

        this.$parent.fetchTasks(); 

    
        this.$emit('task-updated', this.task);

        console.log('Submitted Task Data:', this.task);

      } catch (error) {
        console.error(error.response.data);
      }
    }
  }
};
</script>

<style>

</style>
