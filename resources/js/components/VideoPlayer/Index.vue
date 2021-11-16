<template>
  <div>
    <div class="row">
      <div class="col">
        <video class="w-100" height="600" controls id="videoPlayer">
          <source
            id="source"
            src="/assets/videos/pexels-barbara-olsen-7880881.mp4"
            type="video/mp4"
          />
          Your browser does not support HTML video.
        </video>
      </div>

      <div class="col">
        <h4>Click a video to play the video.</h4>

        <ul class="list-group list-group-flush cursor-pointer">
          <li
            class="list-group-item list-group-item-action"
            v-for="(video, index) in videos"
            :key="index"
            @click="changeVideo(video)"
          >
            {{ video.name }}
          </li>
        </ul>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      videos: [
        {
          name: "Sample Video 1",
          url: "/assets/videos/pexels-barbara-olsen-7880881.mp4",
        },
        {
          name: "Sample Video 2",
          url: "/assets/videos/pexels-kelly-lacy-9722139.mp4",
        },
        {
          name: "Sample Video 3",
          url: "/assets/videos/pexels-mikhail-nilov-7677686.mp4",
        },
        {
          name: "Sample Video 4",
          url: "/assets/videos/pexels-mikhail-nilov-7677695.mp4",
        },
        {
          name: "Sample Video 5",
          url: "/assets/videos/pexels-sathyaprabha-rakkimuthu-5613843.mp4",
        },
      ],

      selected: null,
    };
  },

  methods: {
    changeVideo(video) {
      const video_player = document.getElementById("videoPlayer");
      const source = document.getElementById("source");

      source.setAttribute("src", video.url);
      source.setAttribute("type", "video/mp4");

      video_player.load();
      video_player.play();
    },
  },
};
</script>

<style>
</style>