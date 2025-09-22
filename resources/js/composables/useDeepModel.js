import { computed } from 'vue';
import get from 'lodash/get';
import set from 'lodash/set';

export function useDeepModel(modelRef, path) {
  return computed({
    get() {
      return get(modelRef.value, path);
    },
    set(newVal) {
      set(modelRef.value, path, newVal);
    }
  });
}
