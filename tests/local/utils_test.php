<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

declare(strict_types=1);

namespace tiny_richtext\local;

use advanced_testcase;

/**
 * Unit tests for the \tiny_richtext\local\utils class.
 *
 * @package     tiny_richtext
 * @covers      \tiny_richtext\local\utils
 * @copyright   2026 Felix Yeung
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
final class utils_test extends advanced_testcase {
    /**
     * Test get_lines with an empty string.
     */
    public function test_get_lines_empty_string(): void {
        $this->assertSame([], utils::get_lines(''));
    }

    /**
     * Test get_lines with a single line.
     */
    public function test_get_lines_single_line(): void {
        $this->assertSame(['hello'], utils::get_lines('hello'));
    }

    /**
     * Test get_lines with multiple lines.
     */
    public function test_get_lines_multiple_lines(): void {
        $this->assertSame(['a', 'b', 'c'], utils::get_lines("a\nb\nc"));
    }

    /**
     * Test get_lines removes blank lines.
     */
    public function test_get_lines_blank_lines_removed(): void {
        $this->assertSame(['a', 'b'], utils::get_lines("a\n\nb\n\n"));
    }

    /**
     * Test get_lines trims whitespace from each line.
     */
    public function test_get_lines_whitespace_trimmed(): void {
        $this->assertSame(['hello', 'world'], utils::get_lines("  hello  \n  world  "));
    }

    /**
     * Test get_lines with only whitespace and newlines returns empty array.
     */
    public function test_get_lines_only_whitespace(): void {
        $this->assertSame([], utils::get_lines("\n  \n\n  "));
    }

    /**
     * Test get_lines with realistic colour map input.
     */
    public function test_get_lines_realistic_colour_input(): void {
        $input = "#ff0000 Red\n#00ff00 Green";
        $this->assertSame(['#ff0000 Red', '#00ff00 Green'], utils::get_lines($input));
    }

    /**
     * Test get_lines with a single blank line returns empty array.
     */
    public function test_get_lines_single_blank_line(): void {
        $this->assertSame([], utils::get_lines("\n"));
    }

    /**
     * Test get_lines preserves internal spaces within a line.
     */
    public function test_get_lines_preserves_internal_spaces(): void {
        $this->assertSame(['Arial=arial,helvetica,sans-serif'], utils::get_lines('Arial=arial,helvetica,sans-serif'));
    }

    /**
     * Test get_lines with font size formats.
     */
    public function test_get_lines_font_size_formats(): void {
        $input = "8pt\n10pt\n12pt\n14pt\n18pt\n24pt\n36pt";
        $this->assertSame(['8pt', '10pt', '12pt', '14pt', '18pt', '24pt', '36pt'], utils::get_lines($input));
    }
}
