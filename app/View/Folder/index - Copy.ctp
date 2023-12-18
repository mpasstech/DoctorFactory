<!DOCTYPE html>
<html><head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
   <meta charset="UTF-8">
   <meta name="mobile-web-app-capable" content="yes">
   <meta name="theme-color" content="#7f0b00">
   <title>Folder</title>
	<?php echo $this->Html->script(array('jquery.js','sweetalert.min.js')); ?>
	<?php echo $this->Html->css(array('sweetalert.css')); ?>
	<style type="text/css">

		@import url(font-awesome.min.css);
		@import url("https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600");

		/*
            Snapshot by TEMPLATED
            templated.co @templatedco
            Released for free under the Creative Commons Attribution 3.0 license (templated.co/license)
        */

		/* Reset */

		html, body, div, span, applet, object, iframe, h1, h2, h3, h4, h5, h6, p, blockquote, pre, a, abbr, acronym, address, big, cite, code, del, dfn, em, img, ins, kbd, q, s, samp, small, strike, strong, sub, sup, tt, var, b, u, i, center, dl, dt, dd, ol, ul, li, fieldset, form, label, legend, table, caption, tbody, tfoot, thead, tr, th, td, article, aside, canvas, details, embed, figure, figcaption, footer, header, hgroup, menu, nav, output, ruby, section, summary, time, mark, audio, video {
			margin: 0;
			padding: 0;
			border: 0;
			font-size: 100%;
			font: inherit;
			vertical-align: baseline;
		}

		article, aside, details, figcaption, figure, footer, header, hgroup, menu, nav, section {
			display: block;
		}

		body {
			line-height: 1;
		}

		ol, ul {
			list-style: none;
		}

		blockquote, q {
			quotes: none;
		}

		blockquote:before, blockquote:after, q:before, q:after {
			content: '';
			content: none;
		}

		table {
			border-collapse: collapse;
			border-spacing: 0;
		}

		body {
			-webkit-text-size-adjust: none;
		}

		/* Box Model */

		*, *:before, *:after {
			-moz-box-sizing: border-box;
			-webkit-box-sizing: border-box;
			box-sizing: border-box;
		}

		/* Basic */

		@-ms-viewport {
			width: device-width;
		}

		body {
			-ms-overflow-style: scrollbar;
		}

		@media screen and (max-width: 480px) {

			html, body {
				min-width: 320px;
			}

		}

		body {
			background: #fff;
		}

		body.is-loading *, body.is-loading *:before, body.is-loading *:after {
			-moz-animation: none !important;
			-webkit-animation: none !important;
			-ms-animation: none !important;
			animation: none !important;
			-moz-transition: none !important;
			-webkit-transition: none !important;
			-ms-transition: none !important;
			transition: none !important;
		}

		/* Type */

		body {
			background-color: #fff;
			color: #22313F;
		}

		body, input, select, textarea {
			font-family: "Nunito Sans", Arial, Helvetica, sans-serif;
			font-size: 13pt;
			font-weight: 300;
			line-height: 1.65;
		}

		@media screen and (max-width: 1680px) {

			body, input, select, textarea {
				font-size: 11pt;
			}

		}

		@media screen and (max-width: 1280px) {

			body, input, select, textarea {
				font-size: 11pt;
			}

		}

		@media screen and (max-width: 980px) {

			body, input, select, textarea {
				font-size: 11pt;
			}

		}

		@media screen and (max-width: 736px) {

			body, input, select, textarea {
				font-size: 12pt;
			}

		}

		@media screen and (max-width: 480px) {

			body, input, select, textarea {
				font-size: 12pt;
			}

		}

		a {
			text-decoration: underline;
			color: #19B5FE;
		}

		a:hover {
			text-decoration: none;
			color: #019be3;
		}

		strong, b {
			font-weight: 700;
		}

		em, i {
			font-style: italic;
		}

		p {
			margin: 0 0 2em 0;
		}

		h1, h2, h3, h4, h5, h6 {
			font-weight: 700;
			line-height: 1.5;
			margin: 0 0 1em 0;
		}

		h1 a, h2 a, h3 a, h4 a, h5 a, h6 a {
			color: inherit;
			text-decoration: none;
		}

		h1 {
			font-size: 2em;
		}

		@media screen and (max-width: 480px) {

			h1 {
				font-size: 1.5em;
			}

		}

		h2 {
			font-size: 1.75em;
		}

		@media screen and (max-width: 480px) {

			h2 {
				font-size: 1.4em;
			}

		}

		h3 {
			font-size: 1.35em;
		}

		h4 {
			font-size: 1.1em;
		}

		h5 {
			font-size: 0.9em;
		}

		h6 {
			font-size: 0.7em;
		}

		sub {
			font-size: 0.8em;
			position: relative;
			top: 0.5em;
		}

		sup {
			font-size: 0.8em;
			position: relative;
			top: -0.5em;
		}

		blockquote {
			border-left: solid 4px;
			font-style: italic;
			margin: 0 0 2em 0;
			padding: 0.5em 0 0.5em 2em;
		}

		code {
			border-radius: 4px;
			border: solid 1px;
			font-family: "Courier New", monospace;
			font-size: 0.9em;
			margin: 0 0.25em;
			padding: 0.25em 0.65em;
		}

		pre {
			-webkit-overflow-scrolling: touch;
			font-family: "Courier New", monospace;
			font-size: 0.9em;
			margin: 0 0 2em 0;
		}

		pre code {
			display: block;
			line-height: 1.75;
			padding: 1em 1.5em;
			overflow-x: auto;
		}

		hr {
			border: 0;
			border-bottom: solid 1px;
			margin: 2em 0;
		}

		hr.major {
			margin: 3em 0;
		}

		.align-left {
			text-align: left;
		}

		.align-center {
			text-align: center;
		}

		.align-right {
			text-align: right;
		}

		/* Box */

		.box {
			border-radius: 4px;
			border: solid 1px rgba(144, 144, 144, 0.25);
			margin-bottom: 2em;
			padding: 1.5em;
		}

		.box > :last-child,
		.box > :last-child > :last-child,
		.box > :last-child > :last-child > :last-child {
			margin-bottom: 0;
		}

		.box.alt {
			border: 0;
			border-radius: 0;
			padding: 0;
		}

		/* Button */

		input[type="submit"],
		input[type="reset"],
		input[type="button"],
		button,
		.button {
			-moz-appearance: none;
			-webkit-appearance: none;
			-ms-appearance: none;
			appearance: none;
			-moz-transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
			-webkit-transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
			-ms-transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
			transition: background-color 0.2s ease-in-out, color 0.2s ease-in-out;
			background: #19B5FE;
			color: #FFF;
			border-radius: 4px;
			border: 0;
			cursor: pointer;
			display: inline-block;
			font-weight: 700;
			height: 2.85em;
			line-height: 2.95em;
			padding: 0 1.5em;
			text-align: center;
			text-decoration: none;
			white-space: nowrap;
		}

		input[type="submit"].active,
		input[type="reset"].active,
		input[type="button"].active,
		button.active,
		.button.active {
			color: #FFF;
			background: #22313F;
		}

		input[type="submit"].active:hover,
		input[type="reset"].active:hover,
		input[type="button"].active:hover,
		button.active:hover,
		.button.active:hover {
			color: #FFF;
			background: #22313F;
		}

		input[type="submit"]:hover,
		input[type="reset"]:hover,
		input[type="button"]:hover,
		button:hover,
		.button:hover {
			color: #FFF;
			background: #019be3;
		}

		input[type="submit"].alt,
		input[type="reset"].alt,
		input[type="button"].alt,
		button.alt,
		.button.alt {
			background: none;
			box-shadow: 0 0 0 2px rgba(144, 144, 144, 0.25);
		}

		input[type="submit"].alt:hover,
		input[type="reset"].alt:hover,
		input[type="button"].alt:hover,
		button.alt:hover,
		.button.alt:hover {
			background: rgba(144, 144, 144, 0.075);
			box-shadow: 0 0 0 2px rgba(144, 144, 144, 0.25);
		}

		input[type="submit"].icon,
		input[type="reset"].icon,
		input[type="button"].icon,
		button.icon,
		.button.icon {
			padding-left: 1.35em;
		}

		input[type="submit"].icon:before,
		input[type="reset"].icon:before,
		input[type="button"].icon:before,
		button.icon:before,
		.button.icon:before {
			margin-right: 0.5em;
		}

		input[type="submit"].fit,
		input[type="reset"].fit,
		input[type="button"].fit,
		button.fit,
		.button.fit {
			display: block;
			margin: 0 0 1em 0;
			width: 100%;
		}

		input[type="submit"].small,
		input[type="reset"].small,
		input[type="button"].small,
		button.small,
		.button.small {
			font-size: 0.8em;
		}

		input[type="submit"].big,
		input[type="reset"].big,
		input[type="button"].big,
		button.big,
		.button.big {
			font-size: 1.35em;
			padding: 0 2em;
		}

		input[type="submit"].disabled, input[type="submit"]:disabled,
		input[type="reset"].disabled,
		input[type="reset"]:disabled,
		input[type="button"].disabled,
		input[type="button"]:disabled,
		button.disabled,
		button:disabled,
		.button.disabled,
		.button:disabled {
			-moz-pointer-events: none;
			-webkit-pointer-events: none;
			-ms-pointer-events: none;
			pointer-events: none;
			opacity: 0.25;
		}

		@media screen and (max-width: 480px) {

			input[type="submit"],
			input[type="reset"],
			input[type="button"],
			button,
			.button {
				padding: 0;
			}

		}

		/* Contact */

		#contact {
			display: -ms-flexbox;
			-ms-flex-pack: justify;
			display: -moz-flex;
			display: -webkit-flex;
			display: -ms-flex;
			display: flex;
			-moz-flex-wrap: wrap;
			-webkit-flex-wrap: wrap;
			-ms-flex-wrap: wrap;
			flex-wrap: wrap;
			-moz-justify-content: space-between;
			-webkit-justify-content: space-between;
			-ms-justify-content: space-between;
			justify-content: space-between;
			background: #ececec;
		}

		#contact form {
			margin: 0;
		}

		#contact .icons {
			margin-bottom: 1em;
		}

		#contact .column {
			padding: 3.5em 0 1.5em 0;
			padding-left: 3.5em;
			padding-right: 3.5em;
			width: 50%;
		}

		#contact .column.social {
			background: #e6e6e6;
		}

		@media screen and (max-width: 980px) {

			#contact .column {
				padding: 2em 0 0.1em 0;
				padding-left: 2em;
				padding-right: 2em;
			}

		}

		@media screen and (max-width: 736px) {

			#contact .column {
				width: 100%;
			}

		}

		@media screen and (max-width: 480px) {

			#contact .column {
				padding: 1em 0 0.1em 0;
				padding-left: 1em;
				padding-right: 1em;
			}

		}

		/* Form */

		form {
			margin: 0 0 2em 0;
		}

		form .field {
			margin-bottom: 1.5em;
		}

		form .field.half {
			display: inline-block;
			width: 48%;
		}

		form .field.half.first {
			margin-right: 2.5%;
		}

		@media screen and (max-width: 980px) {

			form .field.half {
				display: block;
				width: 100%;
			}

			form .field.half.first {
				margin-right: 0;
			}

		}

		label {
			display: block;
			font-size: 0.9em;
			font-weight: 700;
			margin: 0 0 1em 0;
		}

		input[type="text"],
		input[type="password"],
		input[type="email"],
		select,
		textarea {
			-moz-appearance: none;
			-webkit-appearance: none;
			-ms-appearance: none;
			appearance: none;
			border-radius: 4px;
			border: solid 1px rgba(25, 181, 254, 0.25);
			color: inherit;
			display: block;
			outline: 0;
			padding: 0 1em;
			text-decoration: none;
			width: 100%;
		}

		input[type="text"]:invalid,
		input[type="password"]:invalid,
		input[type="email"]:invalid,
		select:invalid,
		textarea:invalid {
			box-shadow: none;
		}

		input[type="text"]:active, input[type="text"]:focus,
		input[type="password"]:active,
		input[type="password"]:focus,
		input[type="email"]:active,
		input[type="email"]:focus,
		select:active,
		select:focus,
		textarea:active,
		textarea:focus {
			border: solid 1px #19B5FE;
		}

		.select-wrapper {
			text-decoration: none;
			display: block;
			position: relative;
		}

		.select-wrapper:before {
			-moz-osx-font-smoothing: grayscale;
			-webkit-font-smoothing: antialiased;
			font-family: FontAwesome;
			font-style: normal;
			font-weight: normal;
			text-transform: none !important;
		}

		.select-wrapper:before {
			content: '\f078';
			display: block;
			height: 2.75em;
			line-height: 2.75em;
			pointer-events: none;
			position: absolute;
			right: 0;
			text-align: center;
			top: 0;
			width: 2.75em;
		}

		.select-wrapper select::-ms-expand {
			display: none;
		}

		input[type="text"],
		input[type="password"],
		input[type="email"],
		select {
			height: 2.75em;
		}

		textarea {
			padding: 0.75em 1em;
		}

		input[type="checkbox"],
		input[type="radio"] {
			-moz-appearance: none;
			-webkit-appearance: none;
			-ms-appearance: none;
			appearance: none;
			display: block;
			float: left;
			margin-right: -2em;
			opacity: 0;
			width: 1em;
			z-index: -1;
		}

		input[type="checkbox"] + label,
		input[type="radio"] + label {
			text-decoration: none;
			cursor: pointer;
			display: inline-block;
			font-size: 1em;
			font-weight: 300;
			padding-left: 2.4em;
			padding-right: 0.75em;
			position: relative;
		}

		input[type="checkbox"] + label:before,
		input[type="radio"] + label:before {
			-moz-osx-font-smoothing: grayscale;
			-webkit-font-smoothing: antialiased;
			font-family: FontAwesome;
			font-style: normal;
			font-weight: normal;
			text-transform: none !important;
		}

		input[type="checkbox"] + label:before,
		input[type="radio"] + label:before {
			border-radius: 4px;
			border: solid 1px;
			content: '';
			display: inline-block;
			height: 1.65em;
			left: 0;
			line-height: 1.58125em;
			position: absolute;
			text-align: center;
			top: 0;
			width: 1.65em;
		}

		input[type="checkbox"]:checked + label:before,
		input[type="radio"]:checked + label:before {
			content: '\f00c';
		}

		input[type="checkbox"] + label:before {
			border-radius: 4px;
		}

		input[type="radio"] + label:before {
			border-radius: 100%;
		}

		::-webkit-input-placeholder {
			opacity: 1.0;
			color: rgba(34, 49, 63, 0.25);
		}

		:-moz-placeholder {
			opacity: 1.0;
			color: rgba(34, 49, 63, 0.25);
		}

		::-moz-placeholder {
			opacity: 1.0;
			color: rgba(34, 49, 63, 0.25);
		}

		:-ms-input-placeholder {
			opacity: 1.0;
			color: rgba(34, 49, 63, 0.25);
		}

		.formerize-placeholder {
			opacity: 1.0;
			color: rgba(34, 49, 63, 0.25);
		}

		/* Gallery */

		@-moz-keyframes gallery {
			100% {
				opacity: 1;		}
		}

		@-webkit-keyframes gallery {
			100% {
				opacity: 1;		}
		}

		@-ms-keyframes gallery {
			100% {
				opacity: 1;		}
		}

		@keyframes gallery {
			100% {
				opacity: 1;		}
		}

		.gallery {
			padding: 3.5em;
			position: relative;
			overflow: hidden;
			min-height: 37em;
		}

		@media screen and (max-width: 980px) {

			.gallery {
				padding: 2em;
				min-height: 20em;
			}

			.gallery header h2 {
				margin-bottom: 1em;
			}

		}

		@media screen and (max-width: 480px) {

			.gallery {
				padding: 1em;
			}

		}

		.gallery header {
			display: -ms-flexbox;
			-ms-flex-pack: justify;
			display: -moz-flex;
			display: -webkit-flex;
			display: -ms-flex;
			display: flex;
			-moz-flex-wrap: wrap;
			-webkit-flex-wrap: wrap;
			-ms-flex-wrap: wrap;
			flex-wrap: wrap;
			-moz-justify-content: space-between;
			-webkit-justify-content: space-between;
			-ms-justify-content: space-between;
			justify-content: space-between;
		}

		.gallery header.special {
			-moz-justify-content: center;
			-webkit-justify-content: center;
			-ms-justify-content: center;
			justify-content: center;
		}

		@media screen and (max-width: 736px) {

			.gallery header {
				display: block;
			}

		}

		@media screen and (max-width: 480px) {

			.gallery header h2 {
				margin-bottom: .5em;
			}

		}

		.gallery footer {
			text-align: center;
			margin-top: 4em;
		}

		.gallery .content {
			display: -ms-flexbox;
			display: -moz-flex;
			display: -webkit-flex;
			display: -ms-flex;
			display: flex;
			-moz-flex-wrap: wrap;
			-webkit-flex-wrap: wrap;
			-ms-flex-wrap: wrap;
			flex-wrap: wrap;
			-moz-justify-content: -moz-flex-start;
			-webkit-justify-content: -webkit-flex-start;
			-ms-justify-content: -ms-flex-start;
			justify-content: flex-start;
		}

		.gallery .content .media {
			-moz-animation: gallery 0.75s ease-out 0.4s forwards;
			-webkit-animation: gallery 0.75s ease-out 0.4s forwards;
			-ms-animation: gallery 0.75s ease-out 0.4s forwards;
			animation: gallery 0.75s ease-out 0.4s forwards;
			margin: 2px;
			overflow: hidden;
			opacity: 0;
			position: relative;
			width: 48%;
		}

		.gallery .content .media a {
			display: block;
		}

		.gallery .content .media img {
			-moz-transition: -moz-transform 0.2s ease-in-out;
			-webkit-transition: -webkit-transform 0.2s ease-in-out;
			-ms-transition: -ms-transform 0.2s ease-in-out;
			transition: transform 0.2s ease-in-out;
			max-width: 100%;
			height: auto;
			vertical-align: middle;
		}

		.gallery .content .media:hover img {
			-moz-transform: scale(1.075);
			-webkit-transform: scale(1.075);
			-ms-transform: scale(1.075);
			transform: scale(1.075);
		}

		@media screen and (max-width: 736px) {

			.gallery .content .media {
				width: 48%;
				margin: 2px;
			}

		}

		@media screen and (max-width: 480px) {

			.gallery .content .media {
				width: 46%;
				margin: 5px;
			}

		}

		/* Icon */

		.icon {
			text-decoration: none;
			border-bottom: none;
			position: relative;
		}

		.icon:before {
			-moz-osx-font-smoothing: grayscale;
			-webkit-font-smoothing: antialiased;
			font-family: FontAwesome;
			font-style: normal;
			font-weight: normal;
			text-transform: none !important;
		}

		.icon > .label {
			display: none;
		}

		/* Image */

		.image {
			border: 0;
			display: inline-block;
			position: relative;
		}

		.image img {
			display: block;
		}

		.image.left, .image.right {
			max-width: 40%;
		}

		.image.left img, .image.right img {
			width: 100%;
		}

		@media screen and (max-width: 480px) {

			.image.left, .image.right {
				max-width: 100%;
				width: 100%;
			}

		}

		.image.left {
			float: left;
			margin: 0 1.5em 1em 0;
			top: 0.25em;
		}

		@media screen and (max-width: 480px) {

			.image.left {
				float: none;
				margin: 0 0 1em 0;
			}

		}

		.image.right {
			float: right;
			margin: 0 0 1em 1.5em;
			top: 0.25em;
		}

		@media screen and (max-width: 480px) {

			.image.right {
				float: none;
				margin: 0 0 1em 0;
			}

		}

		.image.fit {
			display: block;
			margin: 0 0 2em 0;
			width: 100%;
		}

		.image.fit img {
			width: 100%;
		}

		.image.special {
			border: solid 1px rgba(144, 144, 144, 0.25);
			padding: 1em;
		}

		.image.main {
			display: block;
			margin: 0 0 3em 0;
			width: 100%;
		}

		.image.main img {
			width: 100%;
		}

		/* List */

		ol {
			list-style: decimal;
			margin: 0 0 2em 0;
			padding-left: 1.25em;
		}

		ol li {
			padding-left: 0.25em;
		}

		ul {
			list-style: disc;
			margin: 0 0 2em 0;
			padding-left: 1em;
		}

		ul li {
			padding-left: 0.5em;
		}

		ul.alt {
			list-style: none;
			padding-left: 0;
		}

		ul.alt li {
			border-top: solid 1px;
			padding: 0.5em 0;
		}

		ul.alt li:first-child {
			border-top: 0;
			padding-top: 0;
		}

		ul.tabs {
			list-style: none;
			padding-left: 0;
		}

		ul.tabs li {
			display: inline-block;
		}

		ul.tabs li:first-child {
			padding: 0;
		}

		@media screen and (max-width: 736px) {

			ul.tabs li {
				display: block;
				margin: 0 0 .25em 0;
				padding: 0;
			}

			ul.tabs li .button {
				width: 100%;
			}

		}

		@media screen and (max-width: 480px) {

			ul.tabs {
				margin-bottom: 1em;
			}

		}

		ul.icons {
			cursor: default;
			list-style: none;
			padding-left: 0;
		}

		ul.icons li {
			display: inline-block;
			padding: 0 1em 0 0;
		}

		ul.icons li:last-child {
			padding-right: 0;
		}

		ul.icons li .icon:before {
			font-size: 2em;
		}

		ul.actions {
			cursor: default;
			list-style: none;
			padding-left: 0;
		}

		ul.actions li {
			display: inline-block;
			padding: 0 1em 0 0;
			vertical-align: middle;
		}

		ul.actions li:last-child {
			padding-right: 0;
		}

		ul.actions.small li {
			padding: 0 0.5em 0 0;
		}

		ul.actions.vertical li {
			display: block;
			padding: 1em 0 0 0;
		}

		ul.actions.vertical li:first-child {
			padding-top: 0;
		}

		ul.actions.vertical li > * {
			margin-bottom: 0;
		}

		ul.actions.vertical.small li {
			padding: 0.5em 0 0 0;
		}

		ul.actions.vertical.small li:first-child {
			padding-top: 0;
		}

		ul.actions.fit {
			display: table;
			margin-left: -1em;
			padding: 0;
			table-layout: fixed;
			width: calc(100% + 1em);
		}

		ul.actions.fit li {
			display: table-cell;
			padding: 0 0 0 1em;
		}

		ul.actions.fit li > * {
			margin-bottom: 0;
		}

		ul.actions.fit.small {
			margin-left: -0.5em;
			width: calc(100% + 0.5em);
		}

		ul.actions.fit.small li {
			padding: 0 0 0 0.5em;
		}

		@media screen and (max-width: 480px) {

			ul.actions {
				margin: 0 0 2em 0;
			}

			ul.actions li {
				padding: 1em 0 0 0;
				display: block;
				text-align: center;
				width: 100%;
			}

			ul.actions li:first-child {
				padding-top: 0;
			}

			ul.actions li > * {
				width: 100%;
				margin: 0 !important;
			}

			ul.actions li > *.icon:before {
				margin-left: -2em;
			}

			ul.actions.small li {
				padding: 0.5em 0 0 0;
			}

			ul.actions.small li:first-child {
				padding-top: 0;
			}

		}

		dl {
			margin: 0 0 2em 0;
		}

		dl dt {
			display: block;
			font-weight: 700;
			margin: 0 0 1em 0;
		}

		dl dd {
			margin-left: 2em;
		}

		/* Section/Article */

		section.special, article.special {
			text-align: center;
		}

		section .inner, article .inner {
			padding: 3.5em 0 1.5em 0;
			padding-left: 3.5em;
			padding-right: 3.5em;
		}

		@media screen and (max-width: 980px) {

			section .inner, article .inner {
				padding: 2em 0 0.1em 0;
				padding-right: 2em;
				padding-left: 2em;
			}

		}

		@media screen and (max-width: 480px) {

			section .inner, article .inner {
				padding: 1em 0 0.1em 0;
				padding-right: 1em;
				padding-left: 1em;
			}

		}

		section .columns, article .columns {
			display: -ms-flexbox;
			display: -moz-flex;
			display: -webkit-flex;
			display: -ms-flex;
			display: flex;
			-moz-flex-wrap: wrap;
			-webkit-flex-wrap: wrap;
			-ms-flex-wrap: wrap;
			flex-wrap: wrap;
			-moz-justify-content: space-between;
			-webkit-justify-content: space-between;
			-ms-justify-content: space-between;
			justify-content: space-between;
		}

		section .columns.double .column, article .columns.double .column {
			width: 48%;
		}

		@media screen and (max-width: 980px) {

			section .columns.double .column, article .columns.double .column {
				width: 100%;
			}

		}

		header p {
			position: relative;
			margin: 0 0 1.5em 0;
		}

		header h2 + p {
			font-size: 1.25em;
			margin-top: -1em;
		}

		header h3 + p {
			font-size: 1.1em;
			margin-top: -0.8em;
		}

		header h4 + p,
		header h5 + p,
		header h6 + p {
			font-size: 0.9em;
			margin-top: -0.6em;
		}

		/* Table */

		.table-wrapper {
			-webkit-overflow-scrolling: touch;
			overflow-x: auto;
		}

		table {
			margin: 0 0 2em 0;
			width: 100%;
		}

		table tbody tr {
			border: solid 1px;
			border-left: 0;
			border-right: 0;
		}

		table td {
			padding: 0.75em 0.75em;
		}

		table th {
			font-size: 1.7em;
			font-weight: 700;
			padding: 0 0.75em 0.75em 0.75em;
			text-align: left;
			padding-top:10px;
		}

		table thead {
			border-bottom: solid 2px;
		}

		table tfoot {
			border-top: solid 2px;
		}

		table.alt {
			border-collapse: separate;
		}

		table.alt tbody tr td {
			border: solid 1px;
			border-left-width: 0;
			border-top-width: 0;
		}

		table.alt tbody tr td:first-child {
			border-left-width: 1px;
		}

		table.alt tbody tr:first-child td {
			border-top-width: 1px;
		}

		table.alt thead {
			border-bottom: 0;
		}

		table.alt tfoot {
			border-top: 0;
		}

		/* Wrapper */

		.page-wrap {
			display: -ms-flexbox;
			display: -moz-flex;
			display: -webkit-flex;
			display: -ms-flex;
			display: flex;
			-moz-flex-wrap: nowrap;
			-webkit-flex-wrap: nowrap;
			-ms-flex-wrap: nowrap;
			flex-wrap: nowrap;
			-moz-justify-content: -moz-flex-start;
			-webkit-justify-content: -webkit-flex-start;
			-ms-justify-content: -ms-flex-start;
			justify-content: flex-start;
		}

		.wrapper {
			position: relative;
		}

		.wrapper > .inner {
			margin: 0 auto;
			width: 60em;
		}

		@media screen and (max-width: 1280px) {

			.wrapper > .inner {
				width: 65em;
			}

		}

		@media screen and (max-width: 980px) {

			.wrapper > .inner {
				width: 100%;
			}

		}

		/* Header */

		#header {
			font-weight: 700;
		}

		/* Menu */

		#nav {
			background: #0d1217;
			z-index: 10002;
			position: relative;
			width: 4em;
		}

		#nav ul {
			list-style: none;
			margin: 0;
			padding: 0;
			position: fixed;
			top: 1em;
			left: 0;
		}

		#nav ul li {
			padding: 0;
			width: 3.75em;
			text-align: Center;
			margin-bottom: 1em;
		}

		#nav ul li a {
			color: rgba(255, 255, 255, 0.5);
			text-decoration: none;
			font-size: 1.5em;
		}

		#nav ul li a:hover {
			color: white;
		}

		#nav ul li a.active {
			color: #19B5FE;
		}

		@media screen and (max-width: 736px) {

			#nav ul li {
				width: 3.4em;
			}

		}

		/* Banner */

		body.is-loading #banner > .inner {
			opacity: 0;
			-moz-transform: translateY(1em);
			-webkit-transform: translateY(1em);
			-ms-transform: translateY(1em);
			transform: translateY(1em);
		}

		#banner {
			display: -ms-flexbox;
			-ms-flex-pack: center;
			-ms-flex-align: center;
			padding: 8em 0 6em 0;
			-moz-align-items: center;
			-webkit-align-items: center;
			-ms-align-items: center;
			align-items: center;
			display: -moz-flex;
			display: -webkit-flex;
			display: -ms-flex;
			display: flex;
			-moz-justify-content: center;
			-webkit-justify-content: center;
			-ms-justify-content: center;
			justify-content: center;
			min-height: 75vh;
			height: 75vh;
			position: relative;
			background: #000;
			background-image: url(../../images/banner.jpg);
			background-size: cover;
			background-attachment: fixed;
			background-repeat: no-repeat;
			background-position: center;
			text-align: center;
			color: #FFF;
		}

		#banner:before {
			-moz-transition: opacity 3s ease;
			-webkit-transition: opacity 3s ease;
			-ms-transition: opacity 3s ease;
			transition: opacity 3s ease;
			-moz-transition-delay: 0.25s;
			-webkit-transition-delay: 0.25s;
			-ms-transition-delay: 0.25s;
			transition-delay: 0.25s;
			content: '';
			display: block;
			background-color: #000;
			height: 100%;
			left: 0;
			opacity: 0.65;
			position: absolute;
			top: 0;
			width: 100%;
			z-index: 1;
		}

		#banner .inner {
			-moz-transform: none;
			-webkit-transform: none;
			-ms-transform: none;
			transform: none;
			-moz-transition: opacity 1s ease, transform 1s ease;
			-webkit-transition: opacity 1s ease, transform 1s ease;
			-ms-transition: opacity 1s ease, transform 1s ease;
			transition: opacity 1s ease, transform 1s ease;
			position: relative;
			opacity: 1;
			z-index: 3;
			padding: 0 2em;
		}

		#banner h1 {
			font-size: 4em;
			line-height: 1em;
			margin: 0 0 0.5em 0;
			padding: 0;
			color: #FFF;
		}

		#banner p {
			font-size: 1.5em;
			margin-bottom: 1.75em;
		}

		#banner a {
			color: #FFF;
			text-decoration: none;
		}

		@media screen and (max-width: 1280px) {

			#banner h1 {
				font-size: 3.5em;
			}

		}

		@media screen and (max-width: 736px) {

			#banner {
				background-attachment: scroll;
			}

			#banner h1 {
				font-size: 2.25em;
			}

			#banner p {
				font-size: 1.25em;
			}

		}

		/* Main */

		#main {
			background: #f1f1f1;
			width: 100%;
		}

		#main #header {
			background: #e6e6e6;
			padding: 1.15em 3.5em;
			text-align: right;
		}

		#main #header h1 {
			margin: 0;
			font-size: 1em;
		}

		@media screen and (max-width: 980px) {

			#main #header {
				padding: 1.15em 2em;
			}

		}

		@media screen and (max-width: 736px) {

			#main #header {
				text-align: center;
			}

		}

		/* Footer */

		#footer {
			padding: 4em 0 2em 0;
			background: #f2f2f2;
		}

		#footer .copyright {
			color: #bfbfbf;
			font-size: 0.9em;
			margin: 0 0 2em 0;
			padding: 0 1em;
			text-align: center;
		}

		@media screen and (max-width: 736px) {

			#footer {
				padding: 3em 0 1em 0;
			}

		}

	</style>

   <style>
						* {
					padding:0;
					margin:0;
				}

				body {
					color: #333;
					font: 14px Sans-Serif;
					/*padding: 50px;*/
					/*background: url("/.images/.bg-sm.png"); repeat;*/
				}

				h1 {
					text-align: center;
					padding: 28px;
					margin: 0;
					color: #fff;
					font-size: 34px;
				}
				h2 {
					font-size: 16px;
					text-align: center;
					padding: 0 0 12px 0;
				}

				#container {
					box-shadow: 0 5px 10px -5px rgba(0,0,0,0.5);
					position: relative;
					background: #03a9f5;
					/*padding-top: 50px;*/
					/*max-width: 820px;*/
					margin: 0 auto;
				}
				table {
					background-color: #F3F3F3;
					border-collapse: collapse;
					width: 100%;
					margin: 15px 0;
				}
				th {
					background-color: #5FC830;
					color: #FFF;
					cursor: pointer;
					padding: 5px 10px;
				}
				th small {
					font-size: 9px;
				}
				td, th {
					text-align: left;
				}
				a {
					text-decoration: none;
				}
				td a {
					color: #663300;
					display: block;
					padding: 5px 10px;
					font-size:1.6em;
					word-wrap: break-word;
				}
				th a {
					padding-left: 0
				}
				td:first-of-type a {
					/*background: url(./.images/file.png) no-repeat 10px 50%; */
					padding-left: 35px;
				}
				th:first-of-type {
					padding-left: 35px;
				}
				td:not(:first-of-type) a {
					background-image: none !important;
				}
				tr:nth-of-type(odd) {
					background-color: #E6E6E6;
				}
				tr:hover td {
					background-color:#CACACA;
				}
				tr:hover td a {
					color: #03a9f5;
				}
				.header-images {
					display: inline-block;
					padding: 0 10% 0 10%;
					margin: 15px 0 15px 0;
				}
				.title {
					text-align: center;
				}
				.heading {
					display: block;
					margin: 0 auto;
					max-width: 100%;
				}
				.footer-bricks{
					width:100%;
					margin-bottom: -3px;
				}
				.sound-bar ul li {
					/*background-image: url(./.images/audio.png); */
					padding-left: 20px;
				}
				.below-footer{
					text-align: center;
					padding-top: 25px;
				}
				.below-footer a {
					color: #919191;
				}
				/* icons for file types (icons by famfamfam) */
				/* images */
				table tr td:first-of-type a[href$=".jpg"],
				table tr td:first-of-type a[href$=".png"],
				table tr td:first-of-type a[href$=".svg"],
				table tr td:first-of-type a[href$=".jpeg"]
				/*{background-image: url(./.images/image.png);} */
				/* gifs */
				table tr td:first-of-type a[href$=".gif"]
				/*{background-image: url(./.images/gif.png);}*/
				/* zips */
				table tr td:first-of-type a[href$=".zip"]
				/*{background-image: url(./.images/zip.png);}*/
				/* css */
				table tr td:first-of-type a[href$=".css"]
				/*{background-image: url(./.images/css.png);}*/
				/* docs */
				table tr td:first-of-type a[href$=".doc"],
				table tr td:first-of-type a[href$=".docx"],
				table tr td:first-of-type a[href$=".ppt"],
				table tr td:first-of-type a[href$=".pptx"],
				table tr td:first-of-type a[href$=".pps"],
				table tr td:first-of-type a[href$=".ppsx"],
				table tr td:first-of-type a[href$=".xls"],
				table tr td:first-of-type a[href$=".xlsx"]
				/*{background-image: url(./.images/office.png)}*/
				/* videos */
				table tr td:first-of-type a[href$=".avi"],
				table tr td:first-of-type a[href$=".wmv"],
				table tr td:first-of-type a[href$=".mp4"],
				table tr td:first-of-type a[href$=".mov"],
				table tr td:first-of-type a[href$=".m4a"]
				/*{background-image: url(./.images/video.png);}*/
				/* audio */
				table tr td:first-of-type a[href$=".mp3"],
				table tr td:first-of-type a[href$=".ogg"],
				table tr td:first-of-type a[href$=".aac"],
				table tr td:first-of-type a[href$=".wma"]
				/*{background-image: url(./.images/sound.png);}*/
				/* web pages */
				table tr td:first-of-type a[href$=".html"],
				table tr td:first-of-type a[href$=".htm"],
				table tr td:first-of-type a[href$=".xml"]
				/*{background-image: url(./.images/xml.png);}*/
				table tr td:first-of-type a[href$=".php"]
				/*{background-image: url(./.images/php.png);}*/
				table tr td:first-of-type a[href$=".js"]
				/*{background-image: url(./.images/script.png);}*/
				/* directories */
				table tr.dir td:first-of-type a
				/*{background-image: url(./.images/folder.png);}*/
				@media (min-width: 400px) {
					img.loz {
					  width: 35px;
					}
					.loz {
					  display: inline-block;
					  padding: 0 5% 0 5%;
					  margin: 15px 0 15px 0;
					}
					.footer-bricks {
						display:none;
					}
				}
				@media (min-width: 770px) {
					img.loz {
					  width: 65px;
					}
					.loz {
					  display: inline-block;
					  padding: 0 10% 0 10%;
					  margin: 15px 0 15px 0;
					}
					.footer-bricks {
						display:initial;
					}
				}
   </style>
</head>
<body>
<div id="container">
	<div class="title">
		<h1><?php echo $driveFolder; ?></h1>
	</div>

	<table class="sortable">
	    <thead>
		<tr>
			<th>Filename</th>
			<th>Type</th>
			<th>Size</th>
			<th>Date Modified</th>
		</tr>
	    </thead>
	    <tbody>
	    </tbody>
	</table>
</div>
<script>
	function showData(){
		var htm = '';
		<?php foreach($driveFiles as $file)
		{ ?>
			htm += '<tr class="file"><td width="100"><a href="<?php echo $file['DriveFile']['file_path']; ?>" class="name"><?php echo $file['DriveFile']['file_name']; ?></a></td><td><a href="<?php echo $file['DriveFile']['file_path']; ?>"><?php echo $file['DriveFile']['file_type']; ?></a></td><td><a href="<?php echo $file['DriveFile']['file_path']; ?>"><?php echo $file['DriveFile']['file_size']; ?></a></td><td><a href="<?php echo $file['DriveFile']['file_path']; ?>"><?php echo $file['DriveFile']['modified']; ?></a></td></tr>';
		<?php } ?>
		$('tbody').html(htm);
	}
	<?php if ($driverPin['DriveFolder']['is_locked'] == 'YES'){ ?>

		swal({
			title: "Folder Is Protected!",
			text: "Please Enter Secure Pin:",
			type: "input",
			showCancelButton: true,
			closeOnConfirm: false,
			animation: "slide-from-top",
			inputPlaceholder: "Write something"
		},
		function(inputValue){
			if (inputValue === false) return false;
			if (inputValue === "")
			{
				swal.showInputError("You need to write something!");
				return false
			}
			else if(inputValue === '<?php echo $driverPin["DriveFolder"]["pin"]; ?>')
			{
				showData();
				swal("Nice!", "You Can Access Folder!", "success");
			}
			else
			{
				return false;
			}
		});


	<?php } else { ?>
	showData();
	<?php } ?>
</script>
</body></html>