document.addEventListener('DOMContentLoaded', () => {
  document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', e => {
      if (!confirm(el.dataset.confirm || 'Tem certeza?')) e.preventDefault();
    });
  });
  const productSelect = document.querySelector('#produto-select');
  const productList = document.querySelector('#lista-produtos');
  const addProduct = document.querySelector('#adicionar-produto');
  if (productSelect && productList && addProduct) {
    addProduct.addEventListener('click', () => {
      const id = productSelect.value;
      const label = productSelect.options[productSelect.selectedIndex]?.text;
      if (!id) return;
      if (productList.querySelector(`[data-id="${id}"]`)) return;
      const item = document.createElement('div');
      item.className = 'd-flex justify-content-between align-items-center border rounded p-2 mb-2 bg-white';
      item.dataset.id = id;
      item.innerHTML = `<span>${label.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]))}</span><button type="button" class="btn btn-sm btn-outline-danger">Remover</button><input type="hidden" name="produtos[]" value="${id}">`;
      item.querySelector('button').addEventListener('click', () => item.remove());
      productList.appendChild(item);
    });
  }
});
