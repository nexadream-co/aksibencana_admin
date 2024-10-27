/* swiper.svelte generated by Svelte v3.38.2 */
import {
	SvelteComponent,
	assign,
	attr,
	binding_callbacks,
	check_outros,
	compute_rest_props,
	create_slot,
	detach,
	element,
	exclude_internal_props,
	get_spread_update,
	group_outros,
	init,
	insert,
	safe_not_equal,
	set_attributes,
	transition_in,
	transition_out,
	update_slot
} from "svelte/internal";

import { onMount, onDestroy, beforeUpdate, afterUpdate, tick } from "svelte";
import { uniqueClasses } from "./utils";
const get_default_slot_changes_1 = dirty => ({ data: dirty & /*slideData*/ 32 });
const get_default_slot_context_1 = ctx => ({ data: /*slideData*/ ctx[5] });
const get_default_slot_changes = dirty => ({ data: dirty & /*slideData*/ 32 });
const get_default_slot_context = ctx => ({ data: /*slideData*/ ctx[5] });

// (92:2) {:else}
function create_else_block(ctx) {
	let current;
	const default_slot_template = /*#slots*/ ctx[8].default;
	const default_slot = create_slot(default_slot_template, ctx, /*$$scope*/ ctx[7], get_default_slot_context_1);

	return {
		c() {
			if (default_slot) default_slot.c();
		},
		m(target, anchor) {
			if (default_slot) {
				default_slot.m(target, anchor);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope, slideData*/ 160)) {
					update_slot(default_slot, default_slot_template, ctx, /*$$scope*/ ctx[7], dirty, get_default_slot_changes_1, get_default_slot_context_1);
				}
			}
		},
		i(local) {
			if (current) return;
			transition_in(default_slot, local);
			current = true;
		},
		o(local) {
			transition_out(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (default_slot) default_slot.d(detaching);
		}
	};
}

// (85:2) {#if zoom}
function create_if_block(ctx) {
	let div;
	let div_data_swiper_zoom_value;
	let current;
	const default_slot_template = /*#slots*/ ctx[8].default;
	const default_slot = create_slot(default_slot_template, ctx, /*$$scope*/ ctx[7], get_default_slot_context);

	return {
		c() {
			div = element("div");
			if (default_slot) default_slot.c();
			attr(div, "class", "swiper-zoom-container");

			attr(div, "data-swiper-zoom", div_data_swiper_zoom_value = typeof /*zoom*/ ctx[0] === "number"
			? /*zoom*/ ctx[0]
			: undefined);
		},
		m(target, anchor) {
			insert(target, div, anchor);

			if (default_slot) {
				default_slot.m(div, null);
			}

			current = true;
		},
		p(ctx, dirty) {
			if (default_slot) {
				if (default_slot.p && (!current || dirty & /*$$scope, slideData*/ 160)) {
					update_slot(default_slot, default_slot_template, ctx, /*$$scope*/ ctx[7], dirty, get_default_slot_changes, get_default_slot_context);
				}
			}

			if (!current || dirty & /*zoom*/ 1 && div_data_swiper_zoom_value !== (div_data_swiper_zoom_value = typeof /*zoom*/ ctx[0] === "number"
			? /*zoom*/ ctx[0]
			: undefined)) {
				attr(div, "data-swiper-zoom", div_data_swiper_zoom_value);
			}
		},
		i(local) {
			if (current) return;
			transition_in(default_slot, local);
			current = true;
		},
		o(local) {
			transition_out(default_slot, local);
			current = false;
		},
		d(detaching) {
			if (detaching) detach(div);
			if (default_slot) default_slot.d(detaching);
		}
	};
}

function create_fragment(ctx) {
	let div;
	let current_block_type_index;
	let if_block;
	let div_class_value;
	let current;
	const if_block_creators = [create_if_block, create_else_block];
	const if_blocks = [];

	function select_block_type(ctx, dirty) {
		if (/*zoom*/ ctx[0]) return 0;
		return 1;
	}

	current_block_type_index = select_block_type(ctx, -1);
	if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);

	let div_levels = [
		{
			class: div_class_value = uniqueClasses(`${/*slideClasses*/ ctx[3]}${/*className*/ ctx[2] ? ` ${/*className*/ ctx[2]}` : ""}`)
		},
		{
			"data-swiper-slide-index": /*virtualIndex*/ ctx[1]
		},
		/*$$restProps*/ ctx[6]
	];

	let div_data = {};

	for (let i = 0; i < div_levels.length; i += 1) {
		div_data = assign(div_data, div_levels[i]);
	}

	return {
		c() {
			div = element("div");
			if_block.c();
			set_attributes(div, div_data);
		},
		m(target, anchor) {
			insert(target, div, anchor);
			if_blocks[current_block_type_index].m(div, null);
			/*div_binding*/ ctx[9](div);
			current = true;
		},
		p(ctx, [dirty]) {
			let previous_block_index = current_block_type_index;
			current_block_type_index = select_block_type(ctx, dirty);

			if (current_block_type_index === previous_block_index) {
				if_blocks[current_block_type_index].p(ctx, dirty);
			} else {
				group_outros();

				transition_out(if_blocks[previous_block_index], 1, 1, () => {
					if_blocks[previous_block_index] = null;
				});

				check_outros();
				if_block = if_blocks[current_block_type_index];

				if (!if_block) {
					if_block = if_blocks[current_block_type_index] = if_block_creators[current_block_type_index](ctx);
					if_block.c();
				} else {
					if_block.p(ctx, dirty);
				}

				transition_in(if_block, 1);
				if_block.m(div, null);
			}

			set_attributes(div, div_data = get_spread_update(div_levels, [
				(!current || dirty & /*slideClasses, className*/ 12 && div_class_value !== (div_class_value = uniqueClasses(`${/*slideClasses*/ ctx[3]}${/*className*/ ctx[2] ? ` ${/*className*/ ctx[2]}` : ""}`))) && { class: div_class_value },
				(!current || dirty & /*virtualIndex*/ 2) && {
					"data-swiper-slide-index": /*virtualIndex*/ ctx[1]
				},
				dirty & /*$$restProps*/ 64 && /*$$restProps*/ ctx[6]
			]));
		},
		i(local) {
			if (current) return;
			transition_in(if_block);
			current = true;
		},
		o(local) {
			transition_out(if_block);
			current = false;
		},
		d(detaching) {
			if (detaching) detach(div);
			if_blocks[current_block_type_index].d();
			/*div_binding*/ ctx[9](null);
		}
	};
}

function instance($$self, $$props, $$invalidate) {
	let slideData;
	const omit_props_names = ["zoom","virtualIndex","class"];
	let $$restProps = compute_rest_props($$props, omit_props_names);
	let { $$slots: slots = {}, $$scope } = $$props;
	let { zoom = undefined } = $$props;
	let { virtualIndex = undefined } = $$props;
	let { class: className = undefined } = $$props;
	let slideEl = null;
	let slideClasses = "swiper-slide";
	let swiper = null;
	let eventAttached = false;

	const updateClasses = (_, el, classNames) => {
		if (el === slideEl) {
			$$invalidate(3, slideClasses = classNames);
		}
	};

	const attachEvent = () => {
		if (!swiper || eventAttached) return;
		swiper.on("_slideClass", updateClasses);
		eventAttached = true;
	};

	const detachEvent = () => {
		if (!swiper) return;
		swiper.off("_slideClass", updateClasses);
		eventAttached = false;
	};

	onMount(() => {
		if (typeof virtualIndex === "undefined") return;

		$$invalidate(
			4,
			slideEl.onSwiper = _swiper => {
				swiper = _swiper;
				attachEvent();
			},
			slideEl
		);

		attachEvent();
	});

	afterUpdate(() => {
		if (!slideEl || !swiper) return;

		if (swiper.destroyed) {
			if (slideClasses !== "swiper-slide") {
				$$invalidate(3, slideClasses = "swiper-slide");
			}

			return;
		}

		attachEvent();
	});

	beforeUpdate(() => {
		attachEvent();
	});

	onDestroy(() => {
		if (!swiper) return;
		detachEvent();
	});

	function div_binding($$value) {
		binding_callbacks[$$value ? "unshift" : "push"](() => {
			slideEl = $$value;
			$$invalidate(4, slideEl);
		});
	}

	$$self.$$set = $$new_props => {
		$$props = assign(assign({}, $$props), exclude_internal_props($$new_props));
		$$invalidate(6, $$restProps = compute_rest_props($$props, omit_props_names));
		if ("zoom" in $$new_props) $$invalidate(0, zoom = $$new_props.zoom);
		if ("virtualIndex" in $$new_props) $$invalidate(1, virtualIndex = $$new_props.virtualIndex);
		if ("class" in $$new_props) $$invalidate(2, className = $$new_props.class);
		if ("$$scope" in $$new_props) $$invalidate(7, $$scope = $$new_props.$$scope);
	};

	$$self.$$.update = () => {
		if ($$self.$$.dirty & /*slideClasses*/ 8) {
			$: $$invalidate(5, slideData = {
				isActive: slideClasses.indexOf("swiper-slide-active") >= 0 || slideClasses.indexOf("swiper-slide-duplicate-active") >= 0,
				isVisible: slideClasses.indexOf("swiper-slide-visible") >= 0,
				isDuplicate: slideClasses.indexOf("swiper-slide-duplicate") >= 0,
				isPrev: slideClasses.indexOf("swiper-slide-prev") >= 0 || slideClasses.indexOf("swiper-slide-duplicate-prev") >= 0,
				isNext: slideClasses.indexOf("swiper-slide-next") >= 0 || slideClasses.indexOf("swiper-slide-duplicate-next") >= 0
			});
		}
	};

	return [
		zoom,
		virtualIndex,
		className,
		slideClasses,
		slideEl,
		slideData,
		$$restProps,
		$$scope,
		slots,
		div_binding
	];
}

class Swiper extends SvelteComponent {
	constructor(options) {
		super();
		init(this, options, instance, create_fragment, safe_not_equal, { zoom: 0, virtualIndex: 1, class: 2 });
	}
}

export default Swiper;