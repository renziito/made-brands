<?php
/* @var $this DefaultController */

$this->breadcrumbs = array(
	$this->module->id,
);
?>

<style>
	/* =========================================================
   CLUB SECRETO
   Scoped exclusively to .welcome
   ========================================================= */

	.welcome {
		width: 100%;
		max-width: 720px;
		margin: 0 auto;
		padding: 70px 30px 80px;

		text-align: center;

		color: #161616;

		position: relative;

		/*
     * Entrada inicial
     */
		animation: welcomeEnter 1s cubic-bezier(.22, 1, .36, 1) both;
	}


	/* =========================================================
   TITLE
   ========================================================= */

	.welcome h1 {
		margin: 0;

		font-size: clamp(42px, 7vw, 72px);
		line-height: 0.95;

		font-weight: 800;
		letter-spacing: -3.5px;

		color: #111;

		/*
     * Movimiento muy suave continuo
     */
		animation: titleFloat 5s ease-in-out infinite;
	}


	/*
 * Cada línea aparece por separado
 */

	.welcome h1 span {
		display: block;

		opacity: 0;

		transform:
			translateY(28px) scale(0.96);

		animation:
			titleLineIn 0.9s cubic-bezier(.22, 1, .36, 1) forwards;
	}


	/*
 * Primera línea
 */

	.welcome h1 span:first-child {
		animation-delay: 0.15s;
	}


	/*
 * Segunda línea
 */

	.welcome h1 .highlight {
		margin-top: 8px;

		color: #111;

		position: relative;

		animation-delay: 0.30s;
	}


	/* =========================================================
   GOLD UNDERLINE
   ========================================================= */

	.welcome h1 .highlight::after {
		content: "";

		display: block;

		width: 55px;
		height: 4px;

		margin: 18px auto 0;

		background: #323237;

		border-radius: 999px;

		opacity: 0;

		transform-origin: center;

		animation:
			underlineIn 0.7s cubic-bezier(.22, 1, .36, 1) 0.75s forwards;

		/*
     * Pequeño movimiento después de aparecer
     */
		animation-composition: accumulate;
	}


	/* =========================================================
   MESSAGE
   ========================================================= */

	.welcome .message {
		max-width: 560px;

		margin: 30px auto 0;

		color: #555;

		font-size: 18px;
		line-height: 1.7;

		font-weight: 400;

		opacity: 0;

		transform: translateY(20px);

		animation:
			messageIn 0.9s cubic-bezier(.22, 1, .36, 1) 0.9s forwards;
	}


	.welcome .message strong {
		color: #151515;

		font-weight: 750;

		/*
     * El "estás dentro" tiene un pequeño pulso
     */
		display: inline-block;

		animation:
			insidePulse 3s ease-in-out 2s infinite;
	}


	/* =========================================================
   GIF
   ========================================================= */

	.welcome .gif-container {
		width: 220px;
		height: 180px;

		margin: 32px auto 0;

		display: flex;
		align-items: center;
		justify-content: center;

		position: relative;

		opacity: 0;

		transform:
			translateY(25px) scale(0.92);

		animation:
			gifContainerIn 0.9s cubic-bezier(.22, 1, .36, 1) 1.15s forwards;
	}


	.welcome .gif-container img {
		display: block;

		max-width: 220px;
		max-height: 180px;

		width: auto;
		height: auto;

		object-fit: contain;

		border-radius: 18px;

		opacity: 0;

		filter:
			grayscale(10%) contrast(1.05);

		transform:
			translateY(8px) rotate(-1deg);

		transition:
			opacity 0.45s ease,
			transform 0.45s ease;

		box-shadow:
			0 18px 40px rgba(0, 0, 0, 0.12);
	}


	.welcome .gif-container img.loaded {
		opacity: 1;

		transform:
			translateY(0) rotate(-1deg);

		/*
     * El GIF sigue flotando suavemente
     */
		animation:
			gifFloat 4s ease-in-out 1.8s infinite;
	}


	/* =========================================================
   FALLBACK
   ========================================================= */

	.welcome .fallback {
		position: absolute;

		display: block;

		font-size: 82px;
		line-height: 1;

		animation:
			fallbackBounce 1.7s ease-in-out infinite;
	}


	/* =========================================================
   ANIMATIONS
   ========================================================= */


	/*
 * Entrada general
 */

	@keyframes welcomeEnter {

		0% {
			opacity: 0;

			transform:
				translateY(25px) scale(0.98);
		}

		100% {
			opacity: 1;

			transform:
				translateY(0) scale(1);
		}
	}


	/*
 * Título: movimiento continuo MUY sutil
 */

	@keyframes titleFloat {

		0%,
		100% {
			transform: translateY(0);
		}

		50% {
			transform: translateY(-4px);
		}
	}


	/*
 * Líneas del título
 */

	@keyframes titleLineIn {

		0% {
			opacity: 0;

			transform:
				translateY(28px) scale(0.96);
		}

		60% {
			opacity: 1;
		}

		100% {
			opacity: 1;

			transform:
				translateY(0) scale(1);
		}
	}


	/*
 * Línea dorada
 */

	@keyframes underlineIn {

		0% {
			opacity: 0;

			transform:
				scaleX(0) rotate(-2deg);
		}

		70% {
			opacity: 1;

			transform:
				scaleX(1.15) rotate(1deg);
		}

		100% {
			opacity: 1;

			transform:
				scaleX(1) rotate(-2deg);
		}
	}


	/*
 * Mensaje
 */

	@keyframes messageIn {

		0% {
			opacity: 0;

			transform: translateY(20px);
		}

		100% {
			opacity: 1;

			transform: translateY(0);
		}
	}


	/*
 * "estás dentro"
 */

	@keyframes insidePulse {

		0%,
		100% {
			transform: scale(1);
		}

		50% {
			transform: scale(1.035);
		}
	}


	/*
 * Contenedor GIF
 */

	@keyframes gifContainerIn {

		0% {
			opacity: 0;

			transform:
				translateY(25px) scale(0.92);
		}

		100% {
			opacity: 1;

			transform:
				translateY(0) scale(1);
		}
	}


	/*
 * GIF flotando
 */

	@keyframes gifFloat {

		0%,
		100% {
			transform:
				translateY(0) rotate(-1deg);
		}

		50% {
			transform:
				translateY(-9px) rotate(1deg);
		}
	}


	/*
 * Emoji fallback
 */

	@keyframes fallbackBounce {

		0%,
		100% {
			transform:
				translateY(0) rotate(-4deg);
		}

		50% {
			transform:
				translateY(-12px) rotate(4deg);
		}
	}


	/* =========================================================
   MOBILE
   ========================================================= */

	@media (max-width: 600px) {

		.welcome {
			padding:
				50px 22px 60px;
		}


		.welcome h1 {
			font-size:
				clamp(40px, 12vw, 58px);

			letter-spacing:
				-2.5px;
		}


		.welcome h1 .highlight {
			margin-top: 6px;
		}


		.welcome h1 .highlight::after {
			width: 48px;
			height: 3px;

			margin-top: 15px;
		}


		.welcome .message {
			margin-top: 26px;

			font-size: 16px;
			line-height: 1.65;
		}


		.welcome .gif-container {
			width: 180px;
			height: 150px;

			margin-top: 28px;
		}


		.welcome .gif-container img {
			max-width: 180px;
			max-height: 150px;
		}


		.welcome .fallback {
			font-size: 70px;
		}
	}


	/* =========================================================
   ACCESSIBILITY
   ========================================================= */

	@media (prefers-reduced-motion: reduce) {

		.welcome,
		.welcome h1,
		.welcome h1 span,
		.welcome h1 .highlight::after,
		.welcome .message,
		.welcome .message strong,
		.welcome .gif-container,
		.welcome .gif-container img,
		.welcome .fallback {
			animation: none !important;

			transition: none !important;
		}

		.welcome h1 span,
		.welcome .message,
		.welcome .gif-container,
		.welcome h1 .highlight::after {
			opacity: 1;

			transform: none;
		}
	}
</style>

<main class="welcome">

	<h1>
		<span>Bienvenido</span>
		<span class="highlight">al club secreto.</span>
	</h1>

	<p class="message">
		Sí... <strong>estás dentro.</strong><br>
		No sabemos exactamente cómo llegaste hasta aquí,
		pero ya que estás... prepárate para divertirte.
	</p>

	<div class="gif-container">

		<div class="fallback" id="fallback">
			😎
		</div>

		<img
			id="randomGif"
			src="https://cataas.com/cat/gif"
			alt="Un gato muy sospechoso">

	</div>

</main>

<script>
	const gif = document.getElementById('randomGif');
	const fallback = document.getElementById('fallback');

	gif.addEventListener('load', function() {
		gif.classList.add('loaded');
		fallback.style.display = 'none';
	});

	gif.addEventListener('error', function() {
		gif.style.display = 'none';
		fallback.style.display = 'block';
	});
</script>