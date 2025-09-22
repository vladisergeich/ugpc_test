<template>
  <tr
    v-dragSource
    v-dropTarget
    :style="{ cursor: 'move', ...pAttrs.style }"
    :class="[pAttrs.className, isOver ? dropClassName : '']"
  >
    <slot />
  </tr>
</template>
<script>
import { DragSource, DropTarget } from "vue-react-dnd";

const TYPE = "DraggableRow";

export default {
  name: "DraggableRow",
  mixins: [DragSource, DropTarget],
  inheritAttrs: false,
  data() {
    return {
      isDragging: false,
      isOver: false,
      dropClassName: "",
    };
  },
  computed: {
    pAttrs() {
      return this.$parent.$attrs;
    },
  },
  dragSource: {
    type: () => TYPE,
    specs: {
      beginDrag() {
        // console.log("beginDrag", this.pAttrs.index);
        return {
          index: this.pAttrs.index,
        };
      },

      endDrag(monitor) {
        this.pAttrs.moveRow(
          monitor.getItem().index,
          monitor.getDropResult().index
        );
      },
    },
    collect(collect, monitor) {
      this.isDragging = monitor.isDragging();
    },
  },
  dropTarget: {
    type: () => TYPE,
    collect(collect, monitor) {
      const { index } = this.pAttrs;
      const { index: dragIndex } = monitor.getItem() || {};
      if (dragIndex === index) {
        return;
      }

      this.isOver = monitor.isOver();
      this.dropClassName =
        dragIndex < index ? " drop-over-downward" : " drop-over-upward";
    },
    specs: {
      drop() {
        return {
          index: this.pAttrs.index,
        };
      },
    },
  },
};
</script>
<style scoped>
.drop-over-downward td {
  border-bottom: 2px dashed #1890ff;
}

.drop-over-upward td {
  border-top: 2px dashed #1890ff;
}
</style>
