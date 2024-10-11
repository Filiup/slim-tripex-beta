import $, { data } from "jquery";
import axios from "axios";
import validator from "validator";

async function post(data) {
  const BaseURL = import.meta.env.VITE_URL;
  console.log("HERE: ", BaseURL);

  try {
    const res = await axios.post(BaseURL, { data: data });

    // Info ohľadom mailu
    $("#pay").hide();
    $(".email_info").text(`Email bol úspešne odoslaný`);
    $(".email_description").html(
      `Link na platbu: <a href=${res.data} target="_blank">${res.data}</a>`
    );
  } catch (err) {
    // Info ohľadom mailu
    $("#pay").hide();
    $(".email_info").text(`Email sa nepodarilo odoslať`);
    $(".email_description").html(err).css("color", "red");
  }
}

function postData(array) {
  let data = {};

  array.forEach((item) => {
    data = { ...data, [item.name]: item.value };
  });

  post(data);
}

function ValidateMail(element) {
  const value = element.val();

  if (value.length === 0) return; // If element has no value then ignore it
  if (!validator.isEmail(value)) element.addClass("is-invalid");
}

function ValidateNumber(element) {
  const value = element.val();

  if (value.length === 0) return; // If element has no value then ignore it
  if (!validator.isNumeric(value)) element.addClass("is-invalid");
}

function ValidateName(element) {
  const value = element.val();

  if (value.length === 0) return; // If element has no value then ignore it
  if (!/^[a-z ,.'-]+$/i.test(value)) element.addClass("is-invalid");
}

$(document).ready(function () {
  $("form").submit(function (event) {
    event.preventDefault();

    $("input").removeClass("is-invalid");

    // Required inputs
    ValidateMail($('input[name="customer_mail"]'));
    ValidateNumber($('input[name="order_amount"]'));
    ValidateNumber($('input[name="order_number"]'));
    ValidateName($('input[name="name_surname"]'));

    // Do not post data to server if something is wrongly typed (has a "is-invalid" class)
    if (!$("input").hasClass("is-invalid")) {
      postData($(this).serializeArray());
    }
  });
});
