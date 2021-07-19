<template>
  <section class="post-listing">
  <div class="post-listing--loading" v-if="loading"></div>

    <form @submit.prevent="fetchPosts(true)" class="post-listing--form">
      <div class="category-listing flex align-center wrapper">
        <h2 class="category-listing--title">Filter Posts by</h2>
        <div v-if="tags.length" class="category-list tags">
          <h6>Topic</h6>
          <select @change="fetchPosts(true)" v-model="topic">
            <option value="">All Topics</option>
            <option v-for="theTopic in topics" :key="theTopic.id" :value="theTopic.term_id">{{ theTopic.name }}</option>
          </select>   
        </div>
        <!--<div v-if="authors.length" class="category-list authors">
          <h6>Author</h6>
          <select @change="fetchPosts(true)" v-model="author">
            <option value="">All Authors</option>
            <option v-for="myAuthor in authors" :value="myAuthor.ID">{{ myAuthor.post_title }}</option>
          </select>   
        </div>-->
      </div>
    </form>

    <div v-if="posts.length" class="wrapper">
      <div v-for="post in posts" :key="post.id" class="post-listing--post">
        <div class="row">
          <div class="col col-sm-6 col-xs-12 post-listing--post--image">
            <a class="post-listing--link" :href="post.permalink"><div v-if="post.image" v-html="post.image" class="image"></div></a>
          </div>
          <div class="col col-sm-6 col-xs-12">
            <div class="editor-content">
              <p v-if="post.category" class="post-listing--post--category category-badge text-white uppercase" :style="'background: '+post.category_color+';'">{{ post.category.name }}</p>
              <h3 class="post-listing--post--title"><a class="post-listing--link" :href="post.permalink">{{ post.post_title }}</a></h3>
              <p v-if="post.author" class="post-listing--post--byline"><span class="post-listing--post--by">by </span><a :href="post.author_permalink" class="post-listing--post--author">{{post.author}}</a></p>
              <p v-if="post.excerpt" v-html="post.excerpt" class="post-listing--post--excerpt"></p>
              <a v-if="post.permalink" :href="post.permalink" class="post-listing--post--read-more">
                <span v-if="post.category.name == 'Podcast'">Listen Now</span>
                <span v-else>Read More</span>
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <nav v-if="pagination && maxPages > 1">
      <button type="button" @click="setPage(1)" v-if="page != 1">First</button>
      <button type="button" @click="setPage(i)" v-for="i in maxPages" :key="i.id">{{ i }}</button>
      <button type="button" @click="setPage(maxPages)" v-if="page != maxPages">Last</button>
    </nav>
    <div class="wrapper show-more--wrapper">
      <button class="button button--dark-blue" v-if="!pagination && this.posts.length < this.totalPosts" type="button" @click="fetchPosts(false,true)">Load More</button>
    </div>   

    <h2 class="wrapper m-b-60" v-if="dirty && !posts.length">Sorry, we can't find anything that matches what you are looking for.</h2>

  </section>
</template>

<script>
const $ = jQuery
import iziSelect from '../../resources/js/plugins/iziSelect'
import helperFunctions from '../../resources/js/plugins/helperFunctions'

export default {
  name: 'post-search',
  props: {
    categories: {
      type: Array,
      default: []
    },
    tags: {
      type: Array,
      default: []
    },
    topics: {
      type: Array,
      default: []
    },
    authors: {
      type: Array,
      default: []
    },
    presetCategory: {
      type: Object,
      default: {}
    },
    presetTopic: {
      type: Object,
      default: {}
    },
    presetTag: {
      type: Object,
      default: {}
    },
    searchTerm: {
      type: Object,
      default: {}
    },
    pagination: {
      type: Boolean,
      default: true
    }
  },
  data () {
    return {
      //user data
      category: '',
      tag: '',
      topic: '',
      search: '',
      author: '',
      // app data
      loading: false,
      posts: [],
      totalPosts: 0,
      page: 1,
      maxPages: 1,
      dirty: false,
      postTypes: 'post'
    }
  },
  mounted(){
    if(Object.keys(this.searchTerm).length){
      this.search = this.searchTerm
      this.postTypes = 'any'
    }

    if(Object.keys(this.presetTag).length){
      this.tag = this.presetTag
    }
    if(Object.keys(this.presetCategory).length){
      this.category = this.presetCategory
    }
    if(Object.keys(this.presetTopic).length){
      this.topic = this.presetTopic.term_id
    }

    this.parseHistory()

    this.$nextTick( () => {
      if (document.querySelector('.category-list.tags')) {
        new iziSelect('.category-list.tags')
      }
      if (document.querySelector('.category-list.authors')) {
        new iziSelect('.category-list.authors')
      }

    })

    this.fetchPosts(true)
  },
  methods: {
    fetchPosts(resetPage, showMore = false){
      if(resetPage){
        this.page = 1
      }
      if (showMore) {
        this.page = this.page + 1
      }

      //go and get the data we need
      this.loading = true
      $.ajax({
        url: izi.ajaxURL, //localized variable
        data: {
          action: 'fetch_posts',
          page: this.page,
          category: this.category,
          search: this.search,
          postTypes: this.postTypes,
          tag: this.tag,
          topic: this.topic,
          author: this.author
        },
        complete: data => {
          const response = data.responseJSON.data

          let lastLoadedIndex = this.posts.length

          if(data.status == 200){
            if (!showMore) {
              this.posts = response.posts  
            } else {
              response.posts.forEach(post => this.posts.push(post))
            }
            this.maxPages = response.maxPages
            this.totalPosts = response.postsFound
            // this.pushHistory()
            this.$nextTick(() => {
              if(lastLoadedIndex != 0){
                window.scrollTo({
                  top: document.querySelectorAll('.post-listing--post')[lastLoadedIndex].offsetTop - document.querySelector('.main-header').offsetHeight,
                  behavior: 'smooth'
                })
              }
            })
          } else {
            alert('Something went wrong. Please try again later.')
          }

          this.loading = false
          this.dirty = true
        }
      })
    },
    setPage(page){
      this.page = page
      this.fetchPosts()
    },
    parseHistory(){
      //go through each GET string, and if we have that key in the vue app, set the value          
      const url = new URL(window.location.href)
      const params = new URLSearchParams(url.search)
      let keyMatchFound = false
      params.forEach((value, key) => {
        if(typeof this[key] != undefined){
          if(key == 'author'){
            this.author = parseInt(value)
          }
          else{
            this[key] = value
          }          
          keyMatchFound = true
        }
      })

      return keyMatchFound
    },
    pushHistory(){
      //pushes history to the URL bar so the back button works
      let url = location.protocol + '//' + location.host + location.pathname + '?'
      const params = [ 'page', 'category', 'tag', 's', 'author', 'topic' ]

      params.forEach((param, index) => {
        if(this[param]){
          url += param + '=' + this[param] + '&'
        }
      })

      //remove the last & if it is there
      url = url[url.length - 1] == '&' ? url.slice(0, -1) : url

      history.pushState(null, document.title, url)
    }
  }
}
</script>
<style>

</style>