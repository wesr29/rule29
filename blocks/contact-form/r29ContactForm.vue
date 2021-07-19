<template lang="pug">
  section.contact-form.bg-dark-blue.text-white
    .wrapper
      .text-center.font-accent.contact-form--title.m-b-25 Hey there, let's chat!
      transition(name="slide" mode="out-in")
        izi-form(@submit="submitForm" v-if="formSubmitted == false")
          template(slot="body")
            izi-input(
              placeholder="Enter First Name"
              label="First Name*"
              rules="required"
              name="first name"
              v-model="firstName"
            )
            izi-input(
              placeholder="Enter Last Name"
              label="Last Name*"
              rules="required"
              name="last name"
              v-model="lastName"
            )
            izi-input(
              placeholder="Enter Email Address"
              label="Email Address*"
              rules="required|email"
              name="email"
              v-model="email"
            )
            izi-select(
              rules="required"
              label="I'm here because I'm..."
              name="looking for choices"
              v-model="helpWith"
              :choices="helpWithChoices"
            )
            izi-input(
              type="textarea"
              placeholder="Enter Message"
              label="Anything you'd like us to know?"
              name="message"
              v-model="message"
            )
        .text-center(v-else)
          .h3 Thanks, we'll be in touch. 
</template>

<script>
  import { IziForm, IziInput, IziSelect } from 'izi-form'

  const $ = jQuery

  export default {
    components: { IziForm, IziInput, IziSelect },
    data(){
      return {
        //app data
        formSubmitted: false,
        helpWithChoices: [
          {
            label: 'Select One',
            value: null,
            disabled: true
          },
          {
            label: 'A prospective client',
            value: 'A prospective client'
          },
          {
            label: 'An existing client',
            value: 'An existing client'
          },
          {
            label: 'A job hunter',
            value: 'A job hunter'
          },
          {
            label: 'An old friend',
            value: 'An old friend'
          },
          {
            label: 'A person who can\'t be defined by categories',
            value: 'A person who can\'t be defined by categories'
          },
          {
            label: 'Looking for a speaker or judge',
            value: 'Looking for a speaker or judge'
          }
        ],

        //user data
        firstName: null,
        lastName: null,
        email: null,
        helpWith: null,
        message: null
      }
    },
    methods: {
      submitForm(){
        $.ajax({
          url: izi.ajaxURL,
          data: {
            action: 'send_contact_form_to_sharpspring',
            firstName: this.firstName,
            lastName: this.lastName,
            email: this.email,
            helpWith: this.helpWith,
            message: this.message
          },
          complete: data => {
            if(data && data.responseJSON && data.responseJSON.success){
              this.formSubmitted = true 
            } else {
              alert('Something went wrong please try again later')
            }
          }
        })

        this.currentStep++
      }
    }
  }
</script>