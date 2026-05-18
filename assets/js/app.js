$(function(){
  // load surveys
  function loadSurveys(){
    $.get('/index.php?r=survey.list', function(resp){
      if(resp.ok){
        let html = '';
        resp.data.forEach(s => {
          html += `<tr><td>${s.reference}</td><td>${s.title}</td><td>${s.status}</td><td>${s.created_at}</td></tr>`;
        });
        $('#surveys-tbody').html(html);
      }
    });
  }
  loadSurveys();

  $('#survey-create-form').on('submit', function(e){
    e.preventDefault();
    const data = {
      reference: $('#ref').val(),
      title: $('#title').val(),
      category: $('#category').val(),
      scope: $('#scope').val(),
      quotes: JSON.parse($('#quotes-json').val() || '[]')
    };
    $.ajax({
      url: '/index.php?r=survey.create',
      method: 'POST',
      data: JSON.stringify(data),
      contentType: 'application/json',
      success(resp){ if(resp.ok){ loadSurveys(); alert('Survey created'); } else alert(resp.error); }
    });
  });

  // basic supplier search
  $('#supplier-search').on('input', function(){
    const q = $(this).val();
    if(q.length < 2) return;
    $.get('/index.php?r=suppliers.search&q='+encodeURIComponent(q), function(r){
      if(r.ok){
        let list = r.data.map(s=>`<li class="list-group-item">${s.name} (${s.rating||'n/a'})</li>`).join('');
        $('#supplier-results').html(list);
      }
    });
  });
});
