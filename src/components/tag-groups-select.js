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

// WCAG contrast: placeholder #646970, border #8c8f94, visible focus ring
const selectTheme = (theme) => ({
  ...theme,
  colors: {
    ...theme.colors,
    primary: "#2271b1",
    neutral20: "#8c8f94",
    neutral30: "#646970",
    neutral40: "#50575e",
    neutral50: "#646970",
    neutral60: "#50575e",
  },
});

const selectStyles = {
  control: (base, state) => ({
    ...base,
    borderColor: state.isFocused ? "#2271b1" : "#8c8f94",
    boxShadow: state.isFocused ? "0 0 0 1px #2271b1" : base.boxShadow,
    outline: state.isFocused ? "2px solid transparent" : base.outline,
    "&:hover": {
      borderColor: state.isFocused ? "#2271b1" : "#50575e",
    },
  }),
  placeholder: (base) => ({
    ...base,
    color: "#646970",
  }),
};

export default function TagGroupsSelect({
  className = "",
  closeOnSelect,
  id,
  inputId,
  styles,
  theme,
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
      inputId={inputId || id}
      styles={{ ...selectStyles, ...(styles || {}) }}
      theme={theme || selectTheme}
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
