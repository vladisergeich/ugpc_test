$("#edit-type-modal").on("show.bs.modal", function(event) {
  const button = $(event.relatedTarget);

  const name = button.data("name");
  const route = button.data("route");
  const criteria = button.data("criteria");
  const modal = $(this);
  modal.find(".modal-body #type-name").val(name);

  modal.find(".modal-body .type-criteria").prop("checked", false);
  modal.find(".modal-body .type-criteria-value option").prop("selected", false);

  criteria.forEach(criterion => {
    modal
      .find(`.modal-body .type-criteria-${criterion.id}`)
      .prop("checked", true);

    modal
      .find(
        `.modal-body .type-criteria-value-${criterion.id} option[value=${criterion.pivot.value}]`
      )
      .prop("selected", criterion.pivot.value);
  });

  modal.find(".modal-body #edit-type-form").attr("action", route);
});

$("#edit-criterion-modal").on("show.bs.modal", function(event) {
  const button = $(event.relatedTarget);

  const name = button.data("name");
  const route = button.data("route");

  const modal = $(this);
  modal.find(".modal-body #criterion-name").val(name);
  modal.find(".modal-body #edit-criterion-form").attr("action", route);
});

$("#edit-participant-role-modal").on("show.bs.modal", function(event) {
  const button = $(event.relatedTarget);

  const name = button.data("name");
  const description = button.data("description");
  const route = button.data("route");
  const objectivesEdit = button.data("objectives-edit");
  const projectEdit = button.data("project-edit");
  const pages = button.data("pages");
  const modal = $(this);

  pages.forEach(page => {
    modal
      .find(`.modal-body #page-edit-${page.id}-show`)
      .prop("checked", false);
    modal
      .find(`.modal-body #page-edit-${page.id}-edit`)
      .prop("checked", false);
  })

  pages.forEach(page => {
    if (page.pivot.show) {
      modal
        .find(`.modal-body #page-edit-${page.id}-show`)
        .prop("checked", true);
    }
    if (page.pivot.edit) {
      modal
        .find(`.modal-body #page-edit-${page.id}-edit`)
        .prop("checked", true);
    }
  })
  modal.find(".modal-body #participant-role-name").val(name);
  modal.find(".modal-body #participant-role-description").val(description);
  modal.find(".modal-body #participant-role-objectives-edit").val(objectivesEdit);
  modal.find(".modal-body #participant-role-project-edit").val(projectEdit);
  modal.find(".modal-body #edit-participant-role-form").attr("action", route);
});

$("#edit-status-modal").on("show.bs.modal", function(event) {
  const button = $(event.relatedTarget);

  const name = button.data("name");
  const display = button.data("display");
  const project_deleted = button.data("project_deleted");
  const route = button.data("route");
  const icon = button.data("icon_");
  const icon_color = button.data("color");
  const chart_color = button.data("chart_color");
  const modal = $(this);

  modal.find(".modal-body #status-name").val(name);
  modal.find(".modal-body #status-icon").val(icon);
  modal.find(".modal-body #status-icon-color").val(icon_color);
  modal.find(".modal-body #status-chart-color").val(chart_color);
  modal.find(".modal-body #status-display").val(display);
  modal.find(".modal-body #status-project_deleted").val(project_deleted);
  modal.find(".modal-body #edit-status-form").attr("action", route);
});

$("#edit-direction-modal").on("show.bs.modal", function(event) {

  const button = $(event.relatedTarget);

  const name = button.data("name");
  const display = button.data("display");
  const route = button.data("route");
  const icon = button.data("icon_");
  const icon_color = button.data("color");
  const modal = $(this);
  const chart_color = button.data("chart_color");

  modal.find(".modal-body #direction-name").val(name);
  modal.find(".modal-body #direction-icon").val(icon);
  modal.find(".modal-body #direction-icon-color").val(icon_color);
  modal.find(".modal-body #direction-chart-color").val(chart_color);
  modal.find(".modal-body #direction-display").val(display);
  modal.find(".modal-body #edit-direction-form").attr("action", route);
});


$("#edit-objective-status-modal").on("show.bs.modal", function(event) {
  const button = $(event.relatedTarget);

  const name = button.data("name");
  const route = button.data("route");
  const icon = button.data("icon_");
  const icon_color = button.data("icon_color");
  const font_weight = button.data("weight");
  const text_decoration = button.data("decoration");
  const text_color = button.data("text_color");
  const objective_end = button.data("end");
  const objective_start = button.data("start");
  const objective_init = button.data("init");
  const modal = $(this);

  modal.find(".modal-body #objective-status-name").val(name);
  modal.find(".modal-body #objective-status-icon").val(icon);
  modal.find(".modal-body #objective-status-icon-color").val(icon_color);
  modal.find(".modal-body #objective-status-text-color").val(text_color);

  modal.find(`.modal-body #objective-status-font-weight option[value=${font_weight}]`).prop('selected', true);
  modal.find(`.modal-body #objective-status-text-decoration option[value=${text_decoration}]`).prop('selected', true);
  modal.find(`.modal-body #objective-status-end option[value=${objective_end}]`).prop('selected', true);
  modal.find(`.modal-body #objective-status-start option[value=${objective_start}]`).prop('selected', true);
  modal.find(`.modal-body #objective-status-init option[value=${objective_init}]`).prop('selected', true);

  modal.find(".modal-body #edit-objective-status-form").attr("action", route);
});

$("#edit-location-modal").on("show.bs.modal", function(event) {

    console.log('locaitons')

    const button = $(event.relatedTarget);

    const name = button.data("name");
    const route = button.data("route");
    const modal = $(this);

    modal.find(".modal-body #location-name").val(name);
    modal.find(".modal-body #edit-location-form").attr("action", route);
});
