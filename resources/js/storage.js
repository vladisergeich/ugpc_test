// export const saveToStorage = (fieldName, value) =>  {
//   let collapsed = localStorage.getItem(fieldName);
//   if (!collapsed) {
//     localStorage.setItem(fieldName, JSON.stringify(obj));
//   } else {
//     collapsed = JSON.parse(collapsed);
//     collapsed[fieldName] = value;
//     localStorage.setItem(fieldName, JSON.stringify(collapsed));
//   }
// }

export const saveToStorage = (key, value) => {
  localStorage.setItem(key, value)
}

export const getFromStorage = (key) => {
  return localStorage.getItem(key)
}

export const removeFromStorage = (key) => {
  localStorage.removeItem(key)
}
