<?php

// MivaVM disassembler
// hacked together in a day or so by slipstream
// The .mva files produced by this disassembler, assuming no errors, should be able to be recompiled by mvasm.

define("MIN_NEXT",0);
define("MAX_NEXT",9);
define("NEXT_NONE",0);
define("NEXT_STRING",1);
define("NEXT_INT",2);
define("NEXT_LABEL",3);
define("NEXT_TWO_STRINGS",4);
define("NEXT_STRING_AND_INT",5);
define("NEXT_TWO_INTS",6);
define("NEXT_FLOAT",7);
define("NEXT_FUNCTION",8);
define("NEXT_LABEL_AND_INT",9);

define("TYPE_STRING",0);
define("TYPE_GLOBAL",1);
define("TYPE_LOCAL",2);
define("TYPE_SYSTEM",3);

// opc() -- defines an opcode in the array of valid opcodes.
function opc($mnem,$next = NEXT_NONE) {
	if (($next > MAX_NEXT) || ($next < MIN_NEXT)) $next = NEXT_NONE;
	return (object)array("mnem"=>$mnem,"next"=>$next);
}

$MivaVMOpcodes = array(
	// here we go.. let's do this properly or not at all...
	// ---
	0x0=>opc("noop"),
	0x7=>opc("pushd"),
	0x9=>opc("pushn"),
	0xb=>opc("pop"),
	0xf=>opc("popd"),
	0x11=>opc("popn"),
	0x13=>opc("spush"),
	0x14=>opc("spop"),
	0x15=>opc("out"),
	0x16=>opc("add"),
	0x17=>opc("sub"),
	0x18=>opc("cat"),
	0x19=>opc("bitand"),
	0x1a=>opc("bitor"),
	0x1b=>opc("bitxor"),
	0x1c=>opc("bitoc"),
	0x1d=>opc("bitsl"),
	0x1e=>opc("bitsr"),
	0x1f=>opc("div"),
	0x20=>opc("mul"),
	0x21=>opc("mod"),
	0x22=>opc("rounds"),
	0x23=>opc("crypt"),
	0x24=>opc("pow"),
	0x25=>opc("roundf"),
	0x2c=>opc("cmp"),
	0x2d=>opc("and"),
	0x2e=>opc("or"),
	0x2f=>opc("in"),
	0x30=>opc("cin"),
	0x31=>opc("ein"),
	0x32=>opc("ecin"),
	0x33=>opc("eq"),
	0x34=>opc("ne"),
	0x35=>opc("ge"),
	0x36=>opc("le"),
	0x37=>opc("lt"),
	0x38=>opc("gt"),
	0x39=>opc("not"),
	0x3a=>opc("dup"),
	0x3b=>opc("ret"),
	0x3c=>opc("retn"),
	0x3e=>opc("elem"),
	0x3f=>opc("elem_ro"),
	0x40=>opc("memb"),
	0x41=>opc("memb_ro"),
	0x42=>opc("store"),
	0x43=>opc("exit"),
	0x45=>opc("do_file"),
	0x46=>opc("do_function"),
	0x47=>opc("hide"),
	0x48=>opc("lockfile"),
	0x49=>opc("lockfile_end"),
	0x51=>opc("out_comp"),
	0x52=>opc("errmsg"),
	0x55=>opc("poprn"),
	0x57=>opc("refer"),
	0x58=>opc("capture"),
	0x59=>opc("capture_end"),
	0x5a=>opc("isnull"),
	
	0x105=>opc("export"),
	0x108=>opc("pop3_delete"),
	0x10b=>opc("smtp_end"),
	
	0x200=>opc("dbopen"),
	0x201=>opc("dbclose"),
	0x202=>opc("dbskip"),
	0x203=>opc("dbgo"),
	0x204=>opc("dbadd"),
	0x205=>opc("dbupdate"),
	0x206=>opc("dbdelete"),
	0x207=>opc("dbundelete"),
	0x208=>opc("dbprimary"),
	0x209=>opc("dbfind"),
	0x20a=>opc("dbsetindex"),
	0x20b=>opc("dbmakeindex"),
	0x20c=>opc("dbpack"),
	0x20d=>opc("dbreindex"),
	0x20e=>opc("dbcreate"),
	0x210=>opc("dbopenview"),
	0x211=>opc("dbcloseview"),
	0x212=>opc("dbquery"),
	0x213=>opc("dbfilter"),
	0x214=>opc("dbreveal"),
	0x215=>opc("dbrevealagg"),
	0x216=>opc("dbopenf"),
	0x217=>opc("dbcommit"),
	0x218=>opc("dbrollback"),
	0x219=>opc("dbtransaction"),
	0x21a=>opc("dbcommand"),
	
	0x4001=>opc("pushc",NEXT_STRING),
	0x4002=>opc("pushi",NEXT_INT),
	0x4004=>opc("pushs",NEXT_STRING), // system
	0x4005=>opc("pushg",NEXT_STRING), // global
	0x4006=>opc("pushl",NEXT_STRING), // local
	0x400a=>opc("pushnc",NEXT_STRING),
	0x400c=>opc("pops",NEXT_STRING), // system
	0x400d=>opc("popg",NEXT_STRING), // global
	0x400e=>opc("popl",NEXT_STRING), // local
	0x4012=>opc("popnc",NEXT_STRING),
	0x4025=>opc("jmp",NEXT_LABEL),
	0x4026=>opc("jmp_eq",NEXT_LABEL),
	0x4027=>opc("jmp_lt",NEXT_LABEL),
	0x4028=>opc("jmp_gt",NEXT_LABEL),
	0x4029=>opc("jmp_le",NEXT_LABEL),
	0x402a=>opc("jmp_ge",NEXT_LABEL),
	0x402b=>opc("jmp_ne",NEXT_LABEL),
	0x403d=>opc("call",NEXT_FUNCTION),
	0x4044=>opc("lineno",NEXT_INT),
	0x404a=>opc("sourcefile",NEXT_STRING),
	0x404c=>opc("erroutput",NEXT_INT),
	0x404d=>opc("poplvnc",NEXT_STRING),
	0x404e=>opc("localize",NEXT_STRING),
	0x404f=>opc("localizev",NEXT_STRING),
	0x4050=>opc("stdoutput",NEXT_INT),
	0x4053=>opc("poprg",NEXT_STRING), // global
	0x4054=>opc("poprl",NEXT_STRING), // local
	0x4056=>opc("poprnc",NEXT_STRING),
	
	0x4101=>opc("import",NEXT_LABEL),
	0x4102=>opc("importf",NEXT_LABEL),
	0x4103=>opc("import_loop",NEXT_LABEL),
	0x4104=>opc("import_stop",NEXT_LABEL),
	0x4106=>opc("pop3",NEXT_LABEL),
	0x4107=>opc("pop3_loop",NEXT_LABEL),
	0x4109=>opc("pop3_stop",NEXT_LABEL),
	0x410a=>opc("smtp",NEXT_LABEL),
	0x410c=>opc("http",NEXT_LABEL),
	0x410d=>opc("http_loop",NEXT_LABEL),
	0x410e=>opc("http_stop",NEXT_LABEL),
	0x410f=>opc("commerce",NEXT_LABEL),
	0x4110=>opc("commerce_loop",NEXT_LABEL),
	0x4111=>opc("commerce_stop",NEXT_LABEL),
	0x4112=>opc("http_cf",NEXT_LABEL),
	
	0x5008=>opc("pushdc",NEXT_TWO_STRINGS),
	0x5010=>opc("popdc",NEXT_TWO_STRINGS),
	0x504b=>opc("tagerror",NEXT_TWO_INTS),
	0x505c=>opc("swap"),
	0x5113=>opc("httpn",NEXT_LABEL_AND_INT),
	0x5301=>opc("cmpgg",NEXT_TWO_STRINGS), // global,global
	0x5302=>opc("cmpgi",NEXT_STRING_AND_INT), // global
	0x5303=>opc("incg",NEXT_STRING), // global
	0x5305=>opc("storegi",NEXT_STRING_AND_INT), // global
	
	0x8003=>opc("pushf",NEXT_FLOAT)
);

// LEtoNumber -- converts a binary word, dword, qword, ... to a PHP number.
function LEtoNumber($bytes) {
	return hexdec(bin2hex(strrev($bytes)));
}

// BEtoFloat -- converts a 64bit float in big endian (as stored in the mvc, for some unknown reason) ... to a PHP float.
function BEtoFloat($bytes) {
	$ret = array_values(unpack('d',$bytes));
	return $ret[0];
}

// DictToText -- dumps the dictionary array into text to be included in the disassembly
function DictToText($key) {
	global $dict;
	if (!property_exists($dict[$key],"name")) throw new Exception("DictToText called before DictToLabel");
	if ($dict[$key]->emitted) return "";
	$dict[$key]->emitted = true;
	return ".".TypeToString($dict[$key]->type)." ".$dict[$key]->name." \"".str_replace('"','\22',str_replace("\\",'\5c',$dict[$key]->string))."\"\n";
}

// TypeToString -- given a TYPE_* define, returns a string
function TypeToString($type) {
	if ($type == TYPE_STRING) return "string";
	if ($type == TYPE_GLOBAL) return "global";
	if ($type == TYPE_LOCAL) return "local";
	if ($type == TYPE_SYSTEM) return "system";
	throw new Exception("TypeToString called with invalid type");
}

// OffsetToDict -- given an unknown offset and an opcode, outputs the correct dictionary key
function OffsetToDict($dictkey,$opmnem) {
	global $globals,$locals,$systems;
	switch ($opmnem) {
			case "pushs":
			case "pops":
				return $systems[$dictkey];
			case "pushg":
			case "popg":
			case "poprg":
			case "cmpgg":
			case "cmpgi":
			case "incg":
			case "storegi":
				return $globals[$dictkey];
			case "pushl":
			case "popl":
			case "pushrl":
			case "poprl":
				return $locals[$dictkey];
			default:
				return $dictkey;
		}
}

// DictToLabel -- given a dictionary item, outputs a label for the disassmbly
function DictToLabel($dictkey,$opmnem) {
	global $dict,$dictNums,$globals,$locals,$systems;
	
	switch ($opmnem) {
		case "pushs":
		case "pops":
			if (!property_exists($dict[$systems[$dictkey]],"name")) {
				$dict[$systems[$dictkey]]->type = TYPE_SYSTEM;
				$dict[$systems[$dictkey]]->name = "sys_".$dictNums->systems;
				$dictNums->systems++;
			}
			return $dict[$systems[$dictkey]]->name;
		case "pushg":
		case "popg":
		case "poprg":
		case "cmpgg":
		case "cmpgi":
		case "incg":
		case "storegi":
			if (!property_exists($dict[$globals[$dictkey]],"name")) {
				$dict[$globals[$dictkey]]->type = TYPE_GLOBAL;
				$dict[$globals[$dictkey]]->name = "g_".$dictNums->globals;
				$dictNums->globals++;
			}
			return $dict[$globals[$dictkey]]->name;
		case "pushl":
		case "popl":
		case "pushrl":
		case "poprl":
			if (!property_exists($dict[$locals[$dictkey]],"name")) {
				$dict[$locals[$dictkey]]->type = TYPE_LOCAL;
				$dict[$locals[$dictkey]]->name = "l_".$dictNums->locals;
				$dictNums->locals++;
			}
			return $dict[$locals[$dictkey]]->name;
		default:
			if (!property_exists($dict[$dictkey],"name")) {
				$dict[$dictkey]->type = TYPE_STRING;
				$dict[$dictkey]->name = "s_".$dictNums->strings;
				$dictNums->strings++;
			}
			return $dict[$dictkey]->name;
	}
}

// FindOffset -- searches the entire disassembly array for a certain offset, used when resolving labels
function FindOffset($offset) {
	global $asm;
	foreach ($asm as $k => $val) {
		//echo $val->offset."\n";
		if ($val->offset == $offset) return $k;
		//elseif ($val->offset + 1 == $offset) return $k;
		//elseif ($val->offset - 1 == $offset) return $k;
		elseif ($val->offset > $offset) return false;
	}
	return false;
}

echo "[+] MivaVM .mvc Disassembler by slipstream: \"y i code this in php\"\n";
if ($argc < 2) die("[-] Usage: ".$argv[0]." file.mvc\n");

$f = file_get_contents($argv[1]);

$magic = substr($f,0,4);

if ($magic != "Miva") die("[-] This file is not a compiled mivascript file.\n");

$offset = 0x4;
$version = LEtoNumber(substr($f,$offset,4)) - 0x10001;

echo "[+] This file is a version ".$version." Miva Script.\n";

// find the dict segment

$dict = strpos($f,"dict",strlen($f)-0x70);
if ($dict === false) die("[-] Can't locate the dict segment :(\n");

$offset = $dict + 4;
$dict_length = LEtoNumber(substr($f,$offset+4,4));
$offset = LEtoNumber(substr($f,$offset,4));

// parse the dictionary

$dict_end = $offset + $dict_length;

$dict_items = LEtoNumber(substr($f,$offset,4));
$offset += 4;
$dict_length = LEtoNumber(substr($f,$offset,4));
$offset += 8;

echo "[+] This file has a dictionary of size ".$dict_length." bytes\n";
echo "[+] This file has ".$dict_items." items in its dictionary\n";

$dict_header = array();

for ($i = 1; $i <= $dict_items; $i++) {
	$item = new stdclass();
	$item->offset = LEtoNumber(substr($f,$offset,4));
	$offset += 4;
	$item->length = LEtoNumber(substr($f,$offset,4));
	$offset += 4;
	$dict_header[] = $item;
}

if (count($dict_header) != $dict_items) die("[-] We got ".count($dict_header)." items in the dictionary, expected ".$dict_items);

$dlen = 0;

$dict = array();

foreach ($dict_header as $item) {
	$doffset = $offset + $item->offset;
	$ditem = new stdclass;
	$ditem->string = substr($f,$doffset,$item->length);
	$ditem->type = TYPE_STRING;
	$ditem->emitted = false;
	$dict[] = $ditem;
	$dlen += $item->length;
}

$offset += $dict_length;

//if ($dlen != $dict_length) die("[-] We got ".$dlen." bytes from the dictionary, expected ".$dict_length);

// find the glbl segment

$glbl = strpos($f,"glbl",strlen($f)-0x70);
if ($glbl === false) die("[-] Can't locate the glbl segment :(\n");

$offset = $glbl + 4;
$glbl_length = LEtoNumber(substr($f,$offset+4,4));
$offset = LEtoNumber(substr($f,$offset,4));

// parse the globals

$glbl_end = $offset + $glbl_length;

$globals = array();
$num_globals = LEtoNumber(substr($f,$offset+3,4));

if ($num_globals > 0) {

	$offset += 7+(6*LEtoNumber(substr($f,$offset,1)));

	if ($offset >= $glbl_end) die("[-] Can't find the first global variable\n");
	
	echo "[+] This file has ".$num_globals." global variables.\n";
	
	for ($i = 1; $i <= $num_globals; $i++) {
		$globals[] = LEtoNumber(substr($f,$offset,4));
		$offset += 8;
	}
}

// find the syst segment

$syst = strpos($f,"syst",strlen($f)-0x70);
if ($syst === false) die("[-] Can't locate the syst segment :(\n");

$offset = $syst + 4;
$syst_length = LEtoNumber(substr($f,$offset+4,4));

$offset = LEtoNumber(substr($f,$offset,4));

// parse the systems

$syst_end = $offset + $syst_length;

$systems = array();
$num_systems = LEtoNumber(substr($f,$offset+3,4));

if ($num_systems > 0) {

	$offset += 7+(6*LEtoNumber(substr($f,$offset,1)));

	if ($offset >= $syst_end) die("[-] Can't find the first system variable\n");
	
	echo "[+] This file has ".$num_systems." system variables.\n";
	
	for ($i = 1; $i <= $num_systems; $i++) {
		$systems[] = LEtoNumber(substr($f,$offset,4));
		$offset += 8;
	}
}

// find the func segment

$func = strpos($f,"func",strlen($f)-0x70);
if ($func === false) die("[-] Can't locate the func segment :(\n");

$offset = $func + 4;
$func_length = LEtoNumber(substr($f,$offset+4,4));

$offset = LEtoNumber(substr($f,$offset,4));

// parse the functions

$func_end = $offset + $func_length;

$functions = array();
$num_functions = LEtoNumber(substr($f,$offset+3,4));

$functions[0] = new stdClass();

$dictNums = new stdClass;
$dictNums->strings = 0;
$dictNums->globals = 0;
$dictNums->locals = 0;
$dictNums->systems = 0;

if ($num_functions > 0) {

	$offset += 7+(6*LEtoNumber(substr($f,$offset,1)))+(4*$num_functions);
	
	if ($offset >= $func_end) die("[-] Can't find the first function\n");
	echo "[+] This file has ".$num_functions." functions.\n";
	
	for ($i = 1; $i <= $num_functions; $i++) {
		$func = new stdClass();
		$params = array();
		$locals = array();
		$func->name = $dict[LEtoNumber(substr($f,$offset,4))]->string;
		$offset += 4;
		$func->numparams = LEtoNumber(substr($f,$offset,4));
		$offset += 4;
		$func->flags = LEtoNumber(substr($f,$offset,4));
		$offset += 12;
		$func->external = ($func->flags & 1);
		for ($j = 1; $j <= $func->numparams; $j++) {
			$param = new stdClass();
			$param->name = $dict[LEtoNumber(substr($f,$offset,4))]->string;
			$offset += 4;
			$param->flags = LEtoNumber(substr($f,$offset,4));
			$offset += 8;
			$params[] = $param;
		}
		$func->params = $params;
		$num_locals = LEtoNumber(substr($f,$offset+3,4));
		if ($num_locals > 0) {
			if (LEtoNumber(substr($f,$offset,1)) == 0) $offset += 7+(6*$num_locals);
			else $offset += 7+(6*LEtoNumber(substr($f,$offset,1)));
			for ($j = 1; $j <= $num_locals; $j++) {
				$locals[] = LEtoNumber(substr($f,$offset,4));
				$offset += 8;
				$dict[$locals[(count($locals) -1)]]->type = TYPE_LOCAL;
				$dict[$locals[(count($locals) -1)]]->name = "l_".(count($locals) -1);
				$dictNums->locals++;
				foreach ($params as $param) {
					if ($dict[$locals[(count($locals) -1)]]->string == $param->name) {
						$dict[$locals[(count($locals) -1)]]->emitted = true;
						break;
					}
				}
			}
		} else $offset += 7;
		$func->locals = $locals;
		$functions[$i] = $func;
	}
}

// find the segs segment
$segs = strpos($f,"segs",strlen($f)-0x70);
if ($segs === false) die("[-] Can't locate the segs segment :(\n");

$offset = $segs + 4;
$segs_length = LEtoNumber(substr($f,$offset+4,4));
$offset = LEtoNumber(substr($f,$offset,4));

// parse the segs segment
$code_end = $offset + $segs_length;
$num_funcs = LEtoNumber(substr($f,$offset,8)); // this might be a qword, better not take any chances
$offset += 8;
for ($i = 0; $i < $num_funcs; $i++) {
	$functions[$i]->offset = LEtoNumber(substr($f,$offset,4));
	$offset += 4;
	$functions[$i]->length = LEtoNumber(substr($f,$offset,4));
	$offset += 4;
}

$functions[0]->external = false;

echo "[+] Disassembling...\n";

$out = "";

$startoff = $offset;

$asm = array();

$labels = array();

$currfunc = 0;
$locals = $globals;

// disassemble
while ($offset < $code_end) {
	$opcode = LEtoNumber(substr($f,$offset,2));
	$mnem = "";
	$next = NEXT_NONE;
	$opoff = $offset - $startoff;
	$offset += 2;
	if (!array_key_exists($opcode,$MivaVMOpcodes)) {
		echo("[-] Found unknown opcode 0x".dechex($opcode)." at offset 0x".dechex($opoff)."\n");
		$asm[] = (object)array("offset"=>$opoff,"line"=>"; Invalid opcode 0x".dechex($opcode)." ; 0x".dechex($opoff));
		continue;
	}
	$opcode = $MivaVMOpcodes[$opcode];
	if ($opcode->mnem == "swap") {
		// hardcoding this because someone decided having the same hex value for two opcodes was a good idea
		if (!array_key_exists(LEtoNumber(substr($f,$offset,2)),$MivaVMOpcodes)) {
			$opcode->mnem = "swapn";
			$opcode->next = NEXT_TWO_INTS;
		}
	}
	if (($currfunc > 0) && (!$functions[$currfunc]->external) && 
		($opoff == $functions[$currfunc]->offset + $functions[$currfunc]->length - 2)) $line = ".endfunction";
	elseif ((!$functions[$currfunc]->external) && ($opoff >= $functions[$currfunc]->offset + $functions[$currfunc]->length)) {
		$currfunc++;
		$line = "\n.function ".$functions[$currfunc]->name;
		
		if ($functions[$currfunc]->flags & 0x2) $line .= " compresswhitespace";
		if ($functions[$currfunc]->flags & 0x4) $line .= " erroroutputlevelon";
		if ($functions[$currfunc]->flags & 0x8) $line .= " erroroutputleveloff";
		if ($functions[$currfunc]->flags & 0x10) $line .= " stdoutinherit";
		$line .= "\n";
		
		$currparam = 0;
		
		foreach ($functions[$currfunc]->params as $param) {
			$line .= ".parameter l_".$currparam." \"".$param->name."\"";
			if ($param->flags & 0x1) $line .= " reference";
			$line .= "\n";
			$currparam++;
		}
		
		$locals = $functions[$currfunc]->locals;
		$line .= "    ".$opcode->mnem;
	}
	else $line = "    ".$opcode->mnem;
	
	switch ($opcode->next) {
		case NEXT_STRING:
			$dictkey = LEtoNumber(substr($f,$offset,4));
			$line .= " ".DictToLabel($dictkey,$opcode->mnem);
			$line = DictToText(OffsetToDict($dictkey,$opcode->mnem)).$line;
			$offset += 4;
			break;
		case NEXT_INT:
			$line .= " ".LEtoNumber(substr($f,$offset,4));
			$offset += 4;
			break;
		case NEXT_LABEL:
			$lbloff = LEtoNumber(substr($f,$offset,4));
			if ($lbloff > 0x7fffffff) $lbloff -= 0x100000000;
			$offset += 4;
			$lbloff += $offset - $startoff;
			if (!in_array($lbloff,$labels)) $labels[] = $lbloff;
			$line .= " [label ".$lbloff."]";
			break;
		case NEXT_TWO_STRINGS:
			$dictkey = LEtoNumber(substr($f,$offset,4));
			$offset += 4;
			$dictkey2 = LEtoNumber(substr($f,$offset,4));
			$line .= " ".DictToLabel($dictkey,$opcode->mnem);
			$label2 = DictToLabel($dictkey2,$opcode->mnem);
			$line = DictToText(OffsetToDict($dictkey,$opcode->mnem)).DictToText(OffsetToDict($dictkey2,$opcode->mnem)).$line.",".$label2;
			$offset += 4;
			break;
		case NEXT_STRING_AND_INT:
			$dictkey = LEtoNumber(substr($f,$offset,4));
			$line .= " ".DictToLabel($dictkey,$opcode->mnem);
			$line = DictToText(OffsetToDict($dictkey,$opcode->mnem)).$line;
			$offset += 4;
			$line .= ",".LEtoNumber(substr($f,$offset,4));
			$offset += 4;
			break;
		case NEXT_TWO_INTS:
			$line .= " ".LEtoNumber(substr($f,$offset,4));
			$offset += 4;
			$line .= ",".LEtoNumber(substr($f,$offset,4));
			$offset += 4;
			break;
		case NEXT_FLOAT:
			$line .= " ".BEtoFloat(substr($f,$offset,8));
			$offset += 8;
			break;
		case NEXT_FUNCTION:
			$line .= " ".$functions[LEtoNumber(substr($f,$offset,4)) +1]->name;
			$offset += 4;
			break;
		case NEXT_LABEL_AND_INT:
			$lbloff = LEtoNumber(substr($f,$offset,4));
			if ($lbloff > 0x7fffffff) $lbloff -= 0x100000000;
			$offset += 4;
			$lbloff += $offset+4 - $startoff;
			if (!in_array($lbloff,$labels)) $labels[] = $lbloff;
			$line .= " [label ".$lbloff."]";
			$line .= ",".LEtoNumber(substr($f,$offset,4));
			$offset += 4;
			break;
	}
	$asm[] = (object)array("offset"=>$opoff,"line"=>$line." ; 0x".dechex($opoff));
}

if ($labels != array()) {
	echo "[+] Resolving labels...\n";

	$resolved = array();
	// resolve unreferenced labels
	for ($i = 0; $i < count($asm); $i++) {
		if (strpos($asm[$i]->line," [label ") !== false) {
			$label = explode(" [label ",$asm[$i]->line);
			$label = (int)(substr($label[1],0,-1));
			$key = array_search($label,$labels);
			if ($key === false) die("[-] Cannot resolve label for offset 0x".dechex($label)."\n");
			if (in_array($label,$resolved)) $asm[$i]->line = str_replace("[label ".$label."]","L_".$key,$asm[$i]->line);
			else {
				// now we need to traverse the entire array (again!) looking for our offset.
				$asmoff = FindOffset($label);
				if ($asmoff === false) die("[-] Cannot resolve label for offset 0x".dechex($label)."\n");
				$lnum = count($resolved);
				$resolved[] = $label;
				$asm[$asmoff]->line = "L_".$lnum.":\n".$asm[$asmoff]->line;
				$asm[$i]->line = str_replace("[label ".$label."]","L_".$lnum,$asm[$i]->line);
			}
		}
	}
}

echo "[+] Writing to file...";

$newname = explode(".",$argv[1]);
$newname[(count($newname) - 1)] = "mva";
$newname = implode(".",$newname);

// put it all together
foreach ($asm as $operation) $out .= $operation->line."\n";

// add external functions
for (; $currfunc < count($functions); $currfunc++) {
	$f = $functions[$currfunc];
	if (!$f->external) continue;
	$out .= "\n.function ".$f->name." external";
	if ($f->flags & 0x2) $out .= " compresswhitespace";
	if ($f->flags & 0x4) $out .= " erroroutputlevelon";
	if ($f->flags & 0x8) $out .= " erroroutputleveloff";
	if ($f->flags & 0x10) $out .= " stdoutinherit";
	$out .= "\n";
	
	$currparam = 0;
	
	foreach ($f->params as $param) {
		$out .= ".parameter l_".$currparam." \"".$param->name."\"";
		if ($param->flags & 0x1) $out .= " reference";
		$out .= "\n";
		$currparam++;
	}
	
	$out .= ".endfunction\n";
}

file_put_contents($newname,$out);
echo "done!\n[+] Disassembled ".basename($argv[1])." to ".$newname."\n";