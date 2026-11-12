import ReactSelect from "react-select";

function getOptionValue(option) {
  if (option && typeof option === "object" && "value" in option) {
    return option.value;
  }

  return option;
}

function valuesMatch(firstValue, secondValue) {
  if (firstValue === null || firstValue === undefined) return false;
  if (secondValue === null || secondValue === undefined) return false;

  return String(firstValue) === String(secondValue);
}

function findSelectedOption(options, value) {
  const selectedValue = getOptionValue(value);

  return (
    options.find((option) => valuesMatch(option.value, selectedValue)) || null
  );
}

function findSelectedOptions(options, values) {
  const selectedValues = Array.isArray(values)
    ? values.map(getOptionValue)
    : [];

  return options.filter((option) =>
    selectedValues.some((value) => valuesMatch(option.value, value)),
  );
}

export default function TagGroupsSelect({
  className = "",
  closeOnSelect,
  multi = false,
  onChange,
  options = [],
  removeSelected,
  value,
  ...props
}) {
  const selectedValue = multi
    ? findSelectedOptions(options, value)
    : findSelectedOption(options, value);

  const handleChange = (selection, actionMeta) => {
    if (multi) {
      onChange(selection || [], actionMeta);
      return;
    }

    if (selection) {
      onChange(selection, actionMeta);
      return;
    }

    onChange(
      {
        label: "",
        value: typeof value === "number" ? -1 : "",
      },
      actionMeta,
    );
  };

  return (
    <ReactSelect
      {...props}
      className={["tag-groups-select", className].filter(Boolean).join(" ")}
      closeMenuOnSelect={closeOnSelect === undefined ? !multi : closeOnSelect}
      hideSelected={removeSelected === undefined ? multi : removeSelected}
      isClearable={true}
      isMulti={multi}
      onChange={handleChange}
      options={options}
      value={selectedValue}
    />
  );
}
