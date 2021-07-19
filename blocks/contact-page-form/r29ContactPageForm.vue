<template lang="pug">
  section.contact-page-form
    .contact-page-form--section.bg-light-blue.p-t-30.p-b-30(v-if="successMessage")
      .wrapper.editor-content
        h2 {{ successMessage }}
    izi-form(v-if="!successMessage" @submit="submit")
      template(slot="body")
        .contact-page-form--section.bg-light-blue.p-t-40.p-b-30
          .wrapper.text-center
            .font-accent.contact-page-form--title.m-b-15 I'm here because I'm...
            izi-select(
              rules="required"
              name="why are you here"
              v-model="userPurpose"
              :choices="userPurposeChoices"
            )
        .contact-page-form--section.bg-white.p-t-40.p-b-40
          .wrapper
            .font-accent.contact-page-form--title.m-b-20 What type of project are you interested in talking about?
            p.text-center Select one, select them all.
            izi-select(
              rules="required"
              type="checkbox"
              name="why are you here"
              v-model="projectChoices"
              :choices="projectTypeChoices"
            )
        .contact-page-form--section.bg-light-blue.p-t-30.p-b-30
          .wrapper
            .row
              izi-input.col.col-sm-6.col-xs-12.text-left(
                placeholder="Enter First Name"
                label="First Name*"
                rules="required"
                name="first name"
                v-model="firstName"
              )
              izi-input.col.col-sm-6.col-xs-12.text-left(
                placeholder="Enter Last Name"
                label="Last Name*"                  
                rules="required"
                name="last name"
                v-model="lastName"
              )
              izi-input.col.col-sm-6.col-xs-12.text-left(
                placeholder="Enter Company"
                label="Company"
                name="company"
                v-model="company"
              )
              izi-input.col.col-sm-6.col-xs-12.text-left(
                placeholder="Enter Email"
                label="Email*"
                rules="required|email"
                name="email"
                v-model="email"
              )
              izi-input.col.col-sm-6.col-xs-12.text-left(
                placeholder="Enter Job Title"
                label="Job Title"
                name="job title"
                v-model="jobTitle"
              )
              izi-input.col.col-sm-6.col-xs-12.text-left(
                placeholder="Enter Phone Number"
                label="Phone Number"
                name="phone"
                v-model="phone"
              )
              izi-input.contact-form--fill-textarea.col.col-sm-6.col-xs-12.text-left(
                type="textarea"
                placeholder="Enter a Message"
                label="Message"
                name="message"
                v-model="message"
              )
              .izi-input.col.col-sm-6.col-xs-12
                label.izi-input--label Are you a robot?
                vue-recaptcha(@verify="captchaConfirmed = true" :sitekey="captchaSiteKey" :loadRecaptchaScript="true")
      .bg-light-blue.p-b-30(slot="submit" slot-scope="{ invalid }")
        .wrapper.text-center
          button.button.button--dark-blue.button--wide(:disabled="invalid") Submit
      
</template>

<script>
  import { IziForm, IziInput, IziSelect } from 'izi-form'
  import VueRecaptcha from 'vue-recaptcha'

  const $ = jQuery

  export default {
    components: { IziForm, IziInput, IziSelect, VueRecaptcha },
    props: {
      captchaSiteKey: String
    },
    data(){
      return {
        //app data
        successMessage: '',
        captchaConfirmed: false,
        userPurposeChoices: [
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
        projectTypeChoices: [
          {
            label: 'Branding',
            value: 'Branding'
          },
          {
            label: 'Strategy',
            value: 'Strategy'
          },
          {
            label: 'Environmental',
            value: 'Environmental'
          },
          {
            label: 'Annual Reports',
            value: 'Annual Reports'
          },
          {
            label: 'Print',
            value: 'Print'
          },
          {
            label: 'Logo & Identity',
            value: 'Logo & Identity'
          },
          {
            label: 'Video/Motion',
            value: 'Video/Motion'
          },
          {
            label: 'Books',
            value: 'Books'
          },
          {
            label: 'Graphics',
            value: 'Graphics'
          },
          {
            label: 'Advertising',
            value: 'Advertising'
          },
          {
            label: 'Packaging',
            value: 'Packaging'
          },
          {
            label: 'Web Sites',
            value: 'Web Sites'
          }
        ],

        //user data
        userPurpose: '',
        projectChoices: [],
        firstName: null,
        lastName: null,
        company: null,
        email: null,
        jobTitle: null,
        phone: null,
        message: null
      }
    },
    methods: {
      submit(){
        if(!this.captchaConfirmed){
          return false
        }

        $.ajax({
          url: izi.ajaxURL,
          data: {
            action : 'send_contact_page_form_to_sharpspring',
            userPurpose : this.userPurpose,
            projectChoices : this.projectChoices,
            firstName : this.firstName,
            lastName : this.lastName,
            company : this.company,
            email : this.email,
            jobTitle : this.jobTitle,
            phone : this.phone,
            message : this.message
          },
          complete: data => {
            if(data && data.responseJSON && data.responseJSON.success){
              this.successMessage = 'Thanks for contacting us. We\'ll be in touch soon.'
            } else {
              alert('Something went wrong please try again later')
            }
          }
        })
      }
    }
  }
</script>