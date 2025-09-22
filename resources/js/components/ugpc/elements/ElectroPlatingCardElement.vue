<template>
    <div class="card_electroplating" :class="{ 'free': electroplating_card_shaft=='-' }">
        <div class="card_electroplating_info">
            <span class="electroplating_card-title" :class="{ 'free_text': electroplating_card_shaft=='-' }">{{electroplating_card_title}}</span>
            <div class="electroplating_card-shaft">
                <span class="electroplating_card_shaft-number" :class="{ 'free_text': electroplating_card_shaft=='-' }">{{ electroplating_card_shaft !== null ? electroplating_card_shaft : '-'}}</span>
                <span class="electroplating_card_shaft-title" :class="{ 'free_text': electroplating_card_shaft=='-' }">вал в работе</span>
                <div style="display: flex; margin-top: 10px; color: black;">
                    <span>Сделано валов:</span>
                    <span style="margin-left: 10px;">{{electroplating_card_qty_operations }}</span>
                </div>
            </div>
        </div>
    </div>
</template>


<script>

export default {
  props: {
    electroplating_card_title: String,
    electroplating_card_shaft: String,
    electroplating_card_qty_operations: Number,
  },
  data() {
    return {

    };
  },
  created() {

  },
  mounted() {
    
  },
  computed: {
  },
  methods: {

  },
};
</script>

<style>

.electroplating_card-icon {
    display: flex;
    width: 50%;
    justify-content: flex-end;
}

.electroplating_card-shaft {
    display: flex;
    flex-direction: column;
    margin-top: 15px;
}
.electroplating_card-title {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 20px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: -0.2px;
}

.electroplating_card_shaft-title {
    color: var(--grayscale-600, #9A9A9A);
    font-family: Open Sans;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: normal;
    letter-spacing: 0.16px;
}

.electroplating_card_shaft-number {
    color: var(--TXT, #363F51);
    font-family: Open Sans;
    font-size: 20px;
    font-style: normal;
    font-weight: 600;
    line-height: normal;
}

.card_electroplating {
    display: flex;
    flex-direction: row;
    padding: 15px;
    cursor: pointer;
    position: relative;
    box-shadow: 0px 4px 20px 0px rgba(189, 189, 189, 0.25);
    border-radius: 10px;
}

.free {
    background: var(--additional-green, #8DD690);
}

.free_text {
    color: var(--grayscale-0, #FFF);
}

.card_electroplating_info {
    display: flex;
    flex-direction: column;
    width: 50%;
}
</style>